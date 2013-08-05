MVC
===
Base interfaces, classes to create MVC-designed application  

###Main concept
It can be used as a base for building MVC applications.  
Most important thing here is interfaces - base of architecture.  

###How it works
Request, parsed in Front Controller, goes to Router, from Router to Controller, Controller fills the Response and this Response goes back to user.      

###Services inside
There is some models, which can be useful:

* Crypt - for passwords hashing and/or bidirectional encryption (based on mcrypt).  
* Sessions - for sessions handling (in Redis by default, but configurable).    
* SessionAuthenticator - work with cookies and CSRF-protection. Implemented AngularJS XSRF-token protection mechanism and prefix for JSON responses.

If you want, you can use some services from Models folder - they are absolutely working and used in live projects.   
In general, this library will never require your classes to be extended from classes of library. Main advice - prefer composition over inheritance.      
But "never require" doesn't mean "not allow" - if you find that some of classes are good enough to use as a base classes - do it, they are designed to be useful in both ways.

###Dependencies
Jamm\\HTTP    
PHP 5.3+  

###Example of Front Controller with auto-fillable routing

	class Launcher
	{
		public function start(array $config = [])
		{
			$ServiceFactory     = new ServiceFactory(new Config($config));
			$ServiceLocator     = new ControllersServiceLocator($ServiceFactory);
			$FallbackController = new IndexPage();
			$RequestParser      = new RequestParser($ServiceFactory->getRequest());
			$Response           = $ServiceLocator->getResponse();
			$Router = new AutoFillableRouter($RequestParser, $FallbackController);
			$Router->fillRoutesFromList(new RoutesList($config['routes']), __NAMESPACE__.'\\Controller', new ControllerBuilder($ServiceLocator));
			$Controller = $Router->getControllerForRequest();
			$Controller->fillResponse($Response);
			$Response->Send();
		}
	}

####config for auto-fillable routing:

	{
    	"routes": {
    		"service": "ServiceAPI",
    		"user": "UserAPI",
    		"record": "RecordAPI"
    	}
    }

####Example of auto-fillable Controller

	class ServiceAPI extends AutoInstantiableController implements IRequireServiceLocator
	{
		public function fillResponse(\Jamm\HTTP\IResponse $Response)
		{
			$action = $this->ServiceLocator->getRequestParser()->getQueryArrayItem(1);
			if ($action=='get_init_data')
			{
				$data = $this->ServiceLocator->getRedis()->get('data');
				$Response->setBody($data);
			}
		}
	}

###Example of Front Controller without auto-filling of routes

	$RedisServer        = new \Jamm\Memory\RedisServer();
    $Request            = new \Jamm\HTTP\Request();
    $RequestParser      = new \Jamm\MVC\Controllers\RequestParser($Request);
    $TemplatesRenderer  = new \Jamm\RedisDashboard\View\TemplatesRenderer();
    $FallbackController = new Controller\Fallback($RedisServer, $TemplatesRenderer);
    $Router             = new \Jamm\MVC\Controllers\Router($RequestParser, $FallbackController);
    $Response           = new \Jamm\HTTP\Response();
    
    $TemplatesRenderer->setBaseURL('/redis');
    $Request->BuildFromInput();
    $Router->addRouteForController('db', new Controller\Database($RedisServer, $RequestParser, $TemplatesRenderer));
    $Router->addRouteForController('key', new Controller\DBKey($RedisServer, $RequestParser, $TemplatesRenderer));
    
    $Response->setHeader('Content-type', 'text/html; charset=UTF-8');
    
    $Controller = $Router->getControllerForRequest();
    $Controller->fillResponse($Response);
    $Response->Send();

###Example of usual (non-autofillable) Controller with Twig template rendering

	class Fallback implements \Jamm\MVC\Controllers\IController
    {
    	private $Redis;
    	private $TemplatesRenderer;
    
    	public function __construct(\Jamm\Memory\IRedisServer $Redis,
    								\Jamm\MVC\Views\ITemplatesRenderer $TemplatesRenderer)
    	{
    		$this->Redis             = $Redis;
    		$this->TemplatesRenderer = $TemplatesRenderer;
    	}
    
    	public function fillResponse(\Jamm\HTTP\IResponse $Response)
    	{
    		$StatsMonitor = $this->getNewStatsMonitor($this->Redis);
    		$stats        = $StatsMonitor->getStats();
    		$databases    = $StatsMonitor->getDatabases();
    
    		$template = $this->TemplatesRenderer->render_Twig_template(
    			'IndexPage.twig', array('databases' => $databases, 'stats' => $stats));
    		$Response->setBody($template);
    	}
    
    	/**
    	 * @param \Jamm\Memory\IRedisServer $Redis
    	 * @return \Jamm\RedisDashboard\Model\StatsMonitor
    	 */
    	protected function getNewStatsMonitor(\Jamm\Memory\IRedisServer $Redis)
    	{
    		return new \Jamm\RedisDashboard\Model\StatsMonitor($Redis);
    	}
    }

###License
[MIT](http://en.wikipedia.org/wiki/MIT_License)

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
