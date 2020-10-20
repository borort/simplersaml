<?php namespace SimplerSaml\Http\Controllers;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SimplerSaml\Events\SamlLogin;
use SimplerSaml\Events\SamlLogout;
use SimplerSaml\Services\SamlAuth;

/**
 * Class SamlController
 *
 * @package SimplerSaml\Http\Controllers
 */
class SamlController extends Controller
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var SamlAuth
     */
    protected $sa;

    protected $event;

    /**
     * @param Config $config
     * @param EventDispatcher $event
     * @param SamlAuth $sa
     */
    public function __construct(Config $config, EventDispatcher $event, SamlAuth $sa)
    {
        $this->sa = $sa;
        $this->event = $event;
        $this->config = $config;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        $samlIdp = $this->config->get('simplersaml.idp');
        $this->sa->requireAuth(array(
            'saml:idp' => $samlIdp,
        ));

        $this->event->dispatch(new SamlLogin($this->sa->user()));

        $loginRedirect = $this->config->get('simplersaml.loginRedirect');

        return redirect()->to($loginRedirect);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        if ($this->sa->isAuthenticated()) {
            $this->sa->logout();
        }

        // Pass through the currently authenticated user
        $this->event->dispatch(new SamlLogout($request->user()));

        $logoutRedirect = $this->config->get('simplersaml.logoutRedirect');

        return redirect()->to($logoutRedirect);
    }
}
