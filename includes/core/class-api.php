<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_API
{
    public $userData;
    public $token;

    private $api_url;
    private $session;
    private $ssl_verify;

    public function __construct()
    {
        $this->api_url = get_option('coimne_api_url', '');
        $this->session = new Coimne_Session();
        $this->ssl_verify = get_option('coimne_dev_mode', false) ? false : true;

        if (empty($this->api_url)) {
            return ['success' => false, 'message' => __('La URL de la API no está configurada.', 'coimne-custom-content')];
        }

        $this->userData = $this->session->get_session('coimne_user_data');
        $this->token = $this->userData ? $this->userData['token'] : '';
    }

    public function verify_recaptcha($recaptcha_response)
    {
        $recaptcha_secret = get_option('coimne_recaptcha_secret_key', '');
        if (empty($recaptcha_secret)) {
            return ['success' => false, 'message' => __('La clave secreta de reCAPTCHA no está configurada.', 'coimne-custom-content')];
        }

        $response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify", [
            'body' => [
                'secret' => $recaptcha_secret,
                'response' => $recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]
        ]);

        if (is_wp_error($response)) {
            return ['success' => false, 'message' => __('Error de conexión con Google reCAPTCHA.', 'coimne-custom-content')];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!$body['success']) {
            return ['success' => false, 'message' => __('La validación de reCAPTCHA ha fallado.', 'coimne-custom-content')];
        }

        return ['success' => true];
    }

    public function authenticate_user($username, $password)
    {
        $contentBody = ['USER' => $username, 'PASS' => $password];
        $data = $this->post('/login', $contentBody);

        if (!$data) {
            return ['success' => false, 'message' => __('No se recibió un token válido.', 'coimne-custom-content')];
        }

        $this->session->set_session('coimne_user_data', $data['desc']);
        $this->token = $data['desc']['token'];

        return [
            'success' => true,
            'token' => $data['desc']['token'],
            'user' => $data['desc']
        ];
    }

    public function get_user_profile()
    {
        $endpoint = "/Perfil";
        $user_id = $this->userData['cttId'];
        $user_type = $this->userData['tip'];
        $params = "?CTT_ID=$user_id&TIP=$user_type";

        $data = $this->get($endpoint . $params);

        if (!$data) {
            return ['success' => false, 'message' => __('Error recibiendo datos', 'coimne-custom-content')];
        }

        return $data['desc']['list'][0];
    }

    public function set_user_profile($profileData)
    {
        $contentBody = [
            'CTT_ID' => $this->userData['cttId'],
            'TIP' => $this->userData['tip'],
            'PAI' => $profileData['PAI'],
            'DIR' => $profileData['DIR'],
            'CPS' => $profileData['CPS'],
            'PRO' => $profileData['PRO'],
            'POB' => $profileData['POB'],
            'TFN' => $profileData['TFN'],
            'TFN_MOV' => $profileData['TFN_MOV'],
            'EML' => $profileData['EML'],
            'LUG_NAC' => $profileData['LUG_NAC'],
            'NAC_NAME' => $profileData['NAC_NAME'],
            'SIT_LAB_NAME' => $profileData['SIT_LAB_NAME'],
            'EST_CIV_NAME' => $profileData['EST_CIV_NAME'],
            'CON_NOM' => $profileData['CON_NOM'],
            'CON_APE_1' => $profileData['CON_APE_1'],
            'CON_APE_2' => $profileData['CON_APE_2'],
            'TIT_FCH' => $profileData['TIT_FCH'],
            'ESC_MIN_NAME' => $profileData['ESC_MIN_NAME'],
            'EMP' => [
                'NAME' => $profileData['EMP_NAME'],
                'PAI' => $profileData['EMP_PAI'],
                'DIR' => $profileData['EMP_DIR'],
                'PRO' => $profileData['EMP_PRO'],
                'POB' => $profileData['EMP_POB'],
                'TFN' => $profileData['EMP_TFN'],
                'TFN_MOV' => $profileData['EMP_TFN_MOV'],
                'EML' => $profileData['EMP_EML'],
            ],
            'EMP_DEP' => $profileData['EMP_DEP'],
            'EMP_CGO' => $profileData['EMP_CGO'],
            'IBAN' => $profileData['IBAN'],
            'SWIFT_BIC' => $profileData['SWIFT_BIC'],
            
        ];
        $data = $this->post('/Perfil', $contentBody);


        return [
            'success' => true, 
            'message' => __('Perfil actualizado correctamente.', 'coimne-custom-content')
        ];
    }

    public function get_countries()
    {
        if (!$this->userData) return [];

        $endpoint = "/Paises";
        $user_id = $this->userData['cttId'];
        $params = "?CTT_ID=$user_id";

        $data = $this->get($endpoint . $params);

        return !$data || empty($data['desc']['list'])
            ? []
            : $data['desc']['list'];
    }

    public function get_provinces($countryId)
    {
        if (!$this->userData) return [];

        $endpoint = "/Provincias";
        $user_id = $this->userData['cttId'];
        $params = "?CTT_ID=$user_id&PAI=$countryId";

        $data = $this->get($endpoint . $params);

        return !$data || empty($data['desc']['list'])
            ? []
            : $data['desc']['list'];
    }

    public function get_towns($countryId, $provinceId, $zipId = 0)
    {
        if (!$this->userData) return [];

        $endpoint = "/Poblaciones";
        $user_id = $this->userData['cttId'];
        $params = "?CTT_ID=$user_id&PAI=$countryId&PRO=$provinceId";

        if ($zipId) $params .= "&CP=$zipId";

        $data = $this->get($endpoint . $params);

        return !$data || empty($data['desc']['list'])
            ? []
            : $data['desc']['list'];
    }

    private function get($endpoint)
    {   
        $finalUrl = rtrim($this->api_url, '/') . $endpoint;

        $response = wp_remote_get($finalUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json'
            ],
            'sslverify' => $this->ssl_verify,
        ]);

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        $this->check_session_status($data);

        if (!$data || empty($data['status']) || empty($data['desc']['list'][0])) {
            return null;
        }

        return $data;
    }

    private function post($endpoint, $body = [])
    {
        $finalUrl = rtrim($this->api_url, '/') . $endpoint;

        $response = wp_remote_post($finalUrl, [
            'body' => http_build_query($body),
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'timeout' => 15,
            'sslverify' => $this->ssl_verify,
        ]);

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['status']) || $data['status'] === false) {
            return null;
        }

        return $data;
    }

    private function check_session_status($response)
    {
        if (!$this->userData || !$this->token) {
            $this->session->clear_session();
            return $this->homeRedirect();
        }

        if (isset($response['desc']['error']) && $response['desc']['error'] === 'Token invalido') {
            $this->session->clear_session();
            return $this->homeRedirect();
        }
    }

    private function homeRedirect()
    {
        if (!headers_sent()) {
            wp_safe_redirect(home_url());
            exit;
        }

        echo '<script>window.location.replace("' . esc_url(home_url()) . '");</script>';
        exit;
    }
}
