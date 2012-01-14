MVC
===
Base interfaces, classes to create MVC-designed application  

###Main concept
This architecture is designed to be used with Dependency Injection.  
It is not complete application and can be used only as base for building MVC applications.  
Here is only 3 classes: QueryParser, Router and View (Fallback and Container is just examples), most important here is interfaces.  

###How it works
Query, parsed in Front Controller, goes to Router, from Router to Controller, Controller fills Response and this Response sends back to user.

###Dependencies
Jamm\\ **HTTP**  
Symfony\\ **Twig**  

###Example of Front Controller
*index.php of one working project*

	$DependenciesContainer = new Factories\Container();
	$QueryParser           = $DependenciesContainer->getQueryParser();
	$FallbackController    = new Controllers\Fallback();
	$Router                = new Controllers\Router($QueryParser, $FallbackController);
	$EntityConverter       = $DependenciesContainer->getEntityConverter();
	
	$Router->addRoute('courier', new NullObjects\Courier($QueryParser, $DependenciesContainer, $EntityConverter));
	$Router->addRoute('help', new NullObjects\Help($DependenciesContainer, $DependenciesContainer));
	$Router->addRoute('order', new NullObjects\Order($QueryParser, $DependenciesContainer));
	
	$Response = $DependenciesContainer->getNewDefaultResponseObject();
	
	$Router->fillResponseForQuery($Response, $QueryParser);
	
	$Response->Send();

###Example of NullObject Controller

	class Courier implements \Jamm\MVC\Factories\IControllerNullObject
	{
		private $QueryParser;
		private $APIClientFactory;
		private $EntityConverter;
	
		public function __construct(\Jamm\MVC\Controllers\IQueryParser $QueryParser, Factories\IAPIClient $APIClientFactory, \Jamm\DataMapper\EntityConverter $EntityConverter)
		{
			$this->QueryParser      = $QueryParser;
			$this->APIClientFactory = $APIClientFactory;
			$this->EntityConverter  = $EntityConverter;
		}
	
		/**
		 * @return IController
		 */
		public function getController()
		{
			$Controller = new Controllers\Courier($this->QueryParser, $this->APIClientFactory, $this->EntityConverter);
			Return $Controller;
		}
	}

###Example of Controller

	class CourierMessages implements \Jamm\MVC\Controllers\IController
	{
		/** @var \Jamm\MVC\Controllers\IQueryParser */
		private $QueryParser;
		/** @var API\IClient */
		private $APIClient;
		/** @var \Jamm\DataMapper\EntityConverter */
		private $EntityConverter;
	
		public function __construct(\Jamm\MVC\Controllers\IQueryParser $QueryParser, Factories\IAPIClient $APIClientFactory, \Jamm\DataMapper\EntityConverter $EntityConverter)
		{
			$this->QueryParser     = $QueryParser;
			$this->APIClient       = $APIClientFactory->getAPIClient();
			$this->EntityConverter = $EntityConverter;
		}
	
		/**
		 * @param \Jamm\HTTP\IResponse $Response
		 */
		public function fillResponse(\Jamm\HTTP\IResponse $Response)
		{
			// And here should be code of controller :)
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
