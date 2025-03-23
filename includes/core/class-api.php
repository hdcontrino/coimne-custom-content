<?php

if (!defined('ABSPATH')) {
    exit;
}

class Coimne_API
{
    public $userData;
    public $userType;
    public $userId;
    public $token;

    private $api_url;
    private $session;
    private $ssl_verify;

    private $unknownError = 'Error desconocido.';

    public function __construct()
    {
        $this->userData = [];
        $this->userType = 'o';
        $this->userId = 0;
        $this->token = '';

        $this->api_url = get_option(COIMNE_OPTION_API_URL, '');
        $this->session = new Coimne_Session();
        $this->ssl_verify = get_option(COIMNE_OPTION_DEV_MODE, false) ? false : true;

        if (empty($this->api_url)) {
            return $this->throwError(null, 'La URL de la API no está configurada.');
        }

        if ($this->userData = $this->session->get_session('coimne_user_data')) {
            $this->userId = $this->userData['cttId'];
            $this->token = $this->userData['token'];
        }

        if ($this->userData && $this->userData['tip']) {
            $this->userType = $this->userData['tip'];
        }
    }

    public function verify_recaptcha($recaptcha_response)
    {
        $recaptcha_secret = get_option(COIMNE_OPTION_RECAPTCHA_SECRET_KEY, '');
        if (empty($recaptcha_secret)) {
            return $this->throwError(null, 'La clave secreta de reCAPTCHA no está configurada.');
        }

        $response = wp_remote_post("https://www.google.com/recaptcha/api/siteverify", [
            'body' => [
                'secret' => $recaptcha_secret,
                'response' => $recaptcha_response,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]
        ]);

        if (is_wp_error($response)) {
            return $this->throwError(null, 'Error de conexión con Google reCAPTCHA.');
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!$body['success']) {
            return $this->throwError(null, 'La validación de reCAPTCHA ha fallado.');
        }

        return [
            'success' => true
        ];
    }

    public function authenticate_user($username, $password)
    {
        $contentBody = [
            'USER' => $username, 
            'PASS' => md5($password)
        ];
        $data = $this->post('/login', $contentBody);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        $this->session->set_session('coimne_user_data', $data['data']);
        $this->token = $data['data']['token'];

        return [
            'success' => true,
            'token' => $data['data']['token'],
            'user' => $data['data']
        ];
    }

    public function forgot_password($formData)
    {
        $contentBody = [
            'USER' => $formData['email'],
        ];
        $data = $this->post('/forgotPass', $contentBody);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => true,
            'message' => $data['message']
        ];
    }

    public function get_user_profile()
    {
        $endpoint = "/Perfil";
        $params = "?CTT_ID=$this->userId&TIP=$this->userType";

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => true,
            'data' => $data['data']['list'][0]
        ];
    }

    public function get_user_courses($query = [])
    {
        $search = array_merge([
            'page' => 1,
            'name' => '',
            'inicioDesde' => '',
            'inicioHasta' => '',
            'inscriptoDesde' => '',
            'inscriptoHasta' => '',
        ], $query);
        $pagination = 3;

        $endpoint = "/MisCursos";
        $params = "?CTT_ID=$this->userId&maxResult=$pagination";
        $params .= "&page=$search[page]&NOM=$search[name]";

        if ($search['inicioDesde']) {
            $desde = Coimne_Helper::format_date_to_backend($search['inicioDesde']);
            $hasta = Coimne_Helper::format_date_to_backend($search['inicioHasta']);
            $hasta = $hasta ?: date('d-m-Y');

            $params .= "&FEC_INI_CUR_DES=$desde&FEC_INI_CUR_HAS=$hasta";
        }

        if ($search['inscriptoDesde']) {
            $desde = Coimne_Helper::format_date_to_backend($search['inscriptoDesde']);
            $hasta = Coimne_Helper::format_date_to_backend($search['inscriptoHasta']);
            $hasta = $hasta ?: date('d-m-Y');

            $params .= "&FEC_INS_DES=$desde&FEC_INS_HAS=$hasta";
        }

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => true,
            'data' => $data['data']['list'],
            'page' => $data['data']['page'],
            'total_pages' => ceil($data['data']['total'] / $pagination),
        ];
    }

    public function get_projects()
    {
        $endpoint = "/Proyectos";
        $params = "?CTT_ID=$this->userId";

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => true,
            'data' => $data['data']['list'][0]
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
            'LOC' => $profileData['LOC'],
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
            'EMP_DEP' => $profileData['EMP_DEP'],
            'EMP_CGO' => $profileData['EMP_CGO'],
            'IBAN' => $profileData['IBAN'],
            'SWIFT_BIC' => $profileData['SWIFT_BIC'],
            
        ];
        $data = $this->post('/Perfil', $contentBody);

        if (!$data['success']) {
            return $this->throwError($data);
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
            return $this->throwError(null, 'Sin cambios.');
        }
        
        if ($username_changes) {
            $contentBody = [
                'CTT_ID' => $this->userData['cttId'],
                'USERNAME' => $accountData['USERNAME'],
                'USERNAMEANT' => $this->userData['userWeb'],
            ];
            $data = $this->post('/ChangeUser', $contentBody);

            if (!$data['success']) {
                return $this->throwError($data);
            }
        }

        if ($password_changes && $accountData['PASS']) {
            $contentBody = [
                'CTT_ID' => $this->userData['cttId'],
                'PASS' => md5($accountData['PASS']),
                'NEWPASS' => md5($accountData['NEWPASS']),
                'USER' => $accountData['USERNAME'],
            ];
            $data = $this->post('/ChangePass', $contentBody);

            if (!$data['success']) {
                return $this->throwError($data);
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
        $params = "?CTT_ID=$this->userId";

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => !empty($data['data']['list']),
            'data' => $data['data']['list']
        ];
    }

    public function get_provinces($countryId)
    {
        $endpoint = "/Provincias";
        $params = "?CTT_ID=$this->userId&PAI=$countryId";

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => !empty($data['data']['list']),
            'data' => $data['data']['list']
        ];
    }

    public function get_towns($countryId, $provinceId, $zipId = 0)
    {
        $endpoint = "/Poblaciones";
        $params = "?CTT_ID=$this->userId&PAI=$countryId&PRO=$provinceId";

        if ($zipId) $params .= "&CP=$zipId";

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => !empty($data['data']['list']),
            'data' => $data['data']['list']
        ];
    }

    public function get_escuelas()
    {
        $endpoint = "/Escuelas";
        $params = "?CTT_ID=$this->userId";

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => !empty($data['data']['list']),
            'data' => $data['data']['list']
        ];
    }

    public function get_cursos()
    {
        $endpoint = "/Cursos";
        $params = "";

        $data = $this->get($endpoint . $params, false);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => !empty($data['data']['list']),
            'data' => $data['data']['list']
        ];
    }

    public function get_empresas($search = '')
    {
        $endpoint = "/Empresas";
        $params = "?CTT_ID=$this->userId&NOM=$search";

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => !empty($data['data']['list']),
            'data' => $data['data']['list']
        ];
    }

    public function submit_enrollment($formData)
    {
        $fields = [
            'curId'   => $formData['CUR_ID']  ?? null,
            'nif'     => $formData['NIF']     ?? null,
            'name'    => $formData['NAME']    ?? null,
            'ape1'    => $formData['APE_1']   ?? null,
            'ape2'    => $formData['APE_2']   ?? null,
            'eml'     => $formData['EML']     ?? null,
            'tfn'     => $formData['TFN']     ?? null,
            'terms'   => $formData['TERMS']   == 'on',
            'privacy' => $formData['PRIVACY'] == 'on',
        ];
        
        foreach ($fields as $each) {
            if (!$each) {
                return $this->throwError(null, 'Faltan campos obligatorios.');
            }
        }

        if (!Coimne_Helper::validar_nif($formData['NIF'])) {
            return $this->throwError(null, 'El NIF ingresado no es válido.');
        }

        if (!Coimne_Helper::validar_telefono($formData['TFN'])) {
            return $this->throwError(null, 'El número de teléfono ingresado no es válido.');
        }

        $contentBody = [
            'CUR_ID' => $fields['curId'],
            'NOM' => $fields['name'],
            'APE_1' => $fields['ape1'],
            'APE_2' => $fields['ape2'],
            'EML' => $fields['eml'],
            'NIF' => $fields['nif'],
            'TFN' => $fields['tfn'],
        ];

        $data = $this->post('/insCurAlt', $contentBody);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => true,
            'message' => __('Inscripción enviada correctamente.', 'coimne-custom-content')
        ];
    }

    public function get_enroll_certificate($insId)
    {
        $endpoint = "/downloadDocCerCur";
        $params = "?CTT_ID=$this->userId&INS_ID=$insId";

        $data = $this->get($endpoint . $params);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => !empty($data['data']),
            'data' => $data['data']
        ];
    }

    public function upload_course_document($inscription_id, $file)
    {
        $body = [
            'CTT_ID' => $this->userId,
            'INS_ID' => $inscription_id
        ];

        $data = $this->postFile('/uploadDocInsCur', $body, ['file' => $file]);

        if (!$data['success']) {
            return $this->throwError($data);
        }

        return [
            'success' => true,
            'message' => __($data['message'] ?: 'Documento subido correctamente.', 'coimne-custom-content')
        ];
    }

    private function get($endpoint, $checkSession = true)
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

        if ($checkSession) {
            $this->check_session_status($data);
        }

        if (!$body || !$data || !isset($data['status']) || !$data['status']) {
            return $this->throwError($data);
        }

        return [
            'success' => $data['status'],
            'message' => $data['message'] ?? '',
            'data' => $data['desc']
        ];
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

        if (!$body || !$data || !isset($data['status']) || !$data['status']) {
            return $this->throwError($data);
        }

        return [
            'success' => $data['status'],
            'message' => $data['message'] ?? '',
            'data' => $data['desc']
        ];
    }

    private function postFile($endpoint, $body = [], $files = [])
    {
        $finalUrl = rtrim($this->api_url, '/') . $endpoint;

        $postData = $body;

        foreach ($files as $field => $file) {
            $postData[$field] = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $finalUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token
            ],
            CURLOPT_SSL_VERIFYPEER => $this->ssl_verify,
            CURLOPT_SSL_VERIFYHOST => $this->ssl_verify ? 2 : 0,
            CURLOPT_TIMEOUT => 15
        ]);

        $response = curl_exec($curl);
        
        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            return $this->throwError(null, $error);
        }
        
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        $data = json_decode($response, true);
        
        if ($httpCode !== 200 || !$data || !isset($data['status']) || !$data['status']) {
            return $this->throwError($data);
        }

        if (isset($data['desc']) && isset($data['desc']['success']) && $data['desc']['success']) {
            $data['message'] = $data['message'] ?: $data['desc']['success'];
        }

        return [
            'success' => true,
            'message' => $data['message'] ?? '',
            'data' => $data['desc'] ?? []
        ];
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

    private function throwError($data, $message = '')
    {
        $message = $message 
            ? __($message, 'coimne-custom-content')
            : __($this->unknownError, 'coimne-custom-content');

        if ($data && isset($data['message']) && $data['message']) {
            $message = $data['message'];
        }

        if ($data && isset($data['desc']) && isset($data['desc']['error'])) {
            $message = $data['desc']['error'];
        }

        if ($data && isset($data['data']) && isset($data['data']['error'])) {
            $message = $data['data']['error'];
        }

        return [
            'success' => false,
            'message' => $message,
            'data' => []
        ];
    }
}
