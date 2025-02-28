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
            return [
                'success' => false, 
                'message' => __('La URL de la API no está configurada.', 'coimne-custom-content')
            ];
        }

        $this->userData = $this->session->get_session('coimne_user_data');
        $this->token = $this->userData ? $this->userData['token'] : '';
    }

    public function verify_recaptcha($recaptcha_response)
    {
        $recaptcha_secret = get_option('coimne_recaptcha_secret_key', '');
        if (empty($recaptcha_secret)) {
            return [
                'success' => false, 
                'message' => __('La clave secreta de reCAPTCHA no está configurada.', 'coimne-custom-content')
            ];
        }

        $response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify", [
            'body' => [
                'secret' => $recaptcha_secret,
                'response' => $recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false, 
                'message' => __('Error de conexión con Google reCAPTCHA.', 'coimne-custom-content')
            ];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!$body['success']) {
            return [
                'success' => false, 
                'message' => __('La validación de reCAPTCHA ha fallado.', 'coimne-custom-content')
            ];
        }

        return [
            'success' => true
        ];
    }

    public function authenticate_user($username, $password)
    {
        $contentBody = ['USER' => $username, 'PASS' => $password];
        $data = $this->post('/login', $contentBody);

        if (!$data['status']) {
            return [
                'success' => false, 
                'message' => $data['desc']['error']
            ];
        }

        $this->session->set_session('coimne_user_data', $data['desc']);
        $this->token = $data['desc']['token'];

        return [
            'success' => true,
            'token' => $data['desc']['token'],
            'user' => $data['desc']
        ];
    }

    public function forgot_password($formData)
    {
        $contentBody = [
            'USER' => $formData['email'],
        ];
        $data = $this->post('/forgotPass', $contentBody);

        if (!$data['status']) {
            return [
                'success' => false,
                'message' => $data['desc']['error']
            ];
        }

        return [
            'success' => true,
            'message' => $data['desc']['success']
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
            return [
                'success' => false, 
                'message' => __('Error recibiendo datos', 'coimne-custom-content')
            ];
        }

        return [
            'success' => true,
            'data' => $data['desc']['list'][0]
        ];
    }

    public function set_user_profile($profileData)
    {
        $fch_nac = Coimne_Helper::format_date_to_backend($profileData['FCH_NAC']);
        $fch_tit = Coimne_Helper::format_date_to_backend($profileData['TIT_FCH']);

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
            'FCH_NAC' => $fch_nac,
            'LUG_NAC' => $profileData['LUG_NAC'],
            'NAC' => $profileData['NAC'],
            'SIT_LAB' => $profileData['SIT_LAB'], 
            'EST_CIV' => $profileData['EST_CIV'],
            'CON_NOM' => $profileData['CON_NOM'],
            'CON_APE_1' => $profileData['CON_APE_1'],
            'CON_APE_2' => $profileData['CON_APE_2'],
            'TIT' => $profileData['TIT'],
            'TIT_FCH' => $fch_tit,
            'ESC_MIN' => $profileData['ESC_MIN'],
            'TS_PRL' => $profileData['TS_PRL'],
            'EMP' => $profileData['EMP'],
            // DATOS DE EMPRESA
            //[
            //    'NAME' => $profileData['EMP_NAME'],
            //    'PAI' => $profileData['EMP_PAI'],
            //    'DIR' => $profileData['EMP_DIR'],
            //    'PRO' => $profileData['EMP_PRO'],
            //    'POB' => $profileData['EMP_POB'],
            //    'TFN' => $profileData['EMP_TFN'],
            //    'TFN_MOV' => $profileData['EMP_TFN_MOV'],
            //    'EML' => $profileData['EMP_EML'],
            //],
            'EMP_DEP' => $profileData['EMP_DEP'],
            'EMP_CGO' => $profileData['EMP_CGO'],
            'IBAN' => $profileData['IBAN'],
            'SWIFT_BIC' => $profileData['SWIFT_BIC'],
            
        ];
        $data = $this->post('/Perfil', $contentBody);

        if (!$data['status']) {
            return [
                'success' => false,
                'message' => $data['desc']['error']
            ];
        }

        return [
            'success' => true, 
            'message' => __('Perfil actualizado correctamente.', 'coimne-custom-content')
        ];
    }

    public function set_user_account($accountData)
    {
        $username_changes = $accountData['USERNAME'] !== $this->userData['userWeb'];
        $password_changes = $accountData['NEWPASS'] !== '';
        
        if (!$username_changes && !$password_changes) {
            return [
                'success' => false,
                'message' => __('Sin cambios.')
            ];
        }
        
        if ($username_changes) {
            $contentBody = [
                'CTT_ID' => $this->userData['cttId'],
                'USERNAME' => $accountData['USERNAME'],
                'USERNAMEANT' => $this->userData['userWeb'],
            ];
            $data = $this->post('/ChangeUser', $contentBody);

            if (!$data['status']) {
                return [
                    'success' => false,
                    'message' => $data['desc']['error']
                ];
            }
        }

        if ($password_changes && $accountData['PASS']) {
            $contentBody = [
                'CTT_ID' => $this->userData['cttId'],
                'PASS' => $accountData['PASS'],
                'NEWPASS' => $accountData['NEWPASS'],
                'USER' => $accountData['USERNAME'],
            ];
            $data = $this->post('/ChangePass', $contentBody);

            if (!$data['status']) {
                return [
                    'success' => false,
                    'message' => $data['desc']['error']
                ];
            }
        }
        
        return [
            'success' => true,
            'message' => __('Cuenta actualizada correctamente.', 'coimne-custom-content')
        ];
    }

    public function get_countries()
    {
        $endpoint = "/Paises";
        $user_id = $this->userData['cttId'] ?: 0;
        $params = "?CTT_ID=$user_id";

        $data = $this->get($endpoint . $params);

        if (!$data['status']) {
            return [
                'success' => false,
                'message' => $data['desc']['error']
            ];
        }

        return [
            'success' => !empty($data['desc']['list']),
            'data' => $data['desc']['list']
        ];
    }

    public function get_provinces($countryId)
    {
        $endpoint = "/Provincias";
        $user_id = $this->userData['cttId'] ?: 0;
        $params = "?CTT_ID=$user_id&PAI=$countryId";

        $data = $this->get($endpoint . $params);

        if (!$data['status']) {
            return [
                'success' => false,
                'message' => $data['desc']['error']
            ];
        }

        return [
            'success' => !empty($data['desc']['list']),
            'data' => $data['desc']['list']
        ];
    }

    public function get_towns($countryId, $provinceId, $zipId = 0)
    {
        $endpoint = "/Poblaciones";
        $user_id = $this->userData['cttId'] ?: 0;
        $params = "?CTT_ID=$user_id&PAI=$countryId&PRO=$provinceId";

        if ($zipId) $params .= "&CP=$zipId";

        $data = $this->get($endpoint . $params);

        if (!$data['status']) {
            return [
                'success' => false,
                'message' => $data['desc']['error']
            ];
        }

        return [
            'success' => !empty($data['desc']['list']),
            'data' => $data['desc']['list']
        ];
    }

    public function get_escuelas()
    {
        $endpoint = "/Escuelas";
        $user_id = $this->userData['cttId'] ?: 0;
        $params = "?CTT_ID=$user_id";

        $data = $this->get($endpoint . $params);

        if (!$data['status']) {
            return [
                'success' => false,
                'message' => $data['desc']['error']
            ];
        }

        return [
            'success' => !empty($data['desc']['list']),
            'data' => $data['desc']['list']
        ];
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

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        $this->check_session_status($data);

        if (!$body || !isset($data['status'])) {
            return [
                'success' => false,
                'message' => __('Error desconocido.', 'coimne-custom-content')
            ];
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

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$body || !isset($data['status'])) {
            return [
                'success' => false,
                'message' => __('Error desconocido.', 'coimne-custom-content')
            ];
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
