<?php
namespace Tests\Controller;

class RunnerController extends \Bliss\Controller\AbstractController
{
	public function runAction()
	{
		$this->_generateConfig();
		
		$result = shell_exec("phpunit -c ". __DIR__ ."/config.xml --bootstrap ". __DIR__ ."/autoload.php");
		
		return "<pre>{$result}</pre>";
	}
	
	private function _generateConfig()
	{
		ob_start();
		$writer = new \XMLWriter("1.0", "UTF-8");
		$writer->openUri("php://output");
		$writer->setIndent(4);
		$writer->startDocument();
		$writer->startElement("testsuites");
		
		foreach ($this->app->modules() as $module) {
			$writer->startElement("testsuite");
				$writer->writeAttribute("name", $module->name());
				$writer->writeElement("directory", $module->resolvePath("src"));
			$writer->endElement();
		}
		
		$writer->endElement();
		$writer->endDocument();
		$writer->flush(true);
		$content = trim(ob_get_clean());
		
		file_put_contents(__DIR__ ."/config.xml", $content);
	}
}