<?php
namespace Tests;

class Result extends \Bliss\Component
{
	const RESULT_UNKNOWN = "unknown";
	const RESULT_ERROR = "error";
	const RESULT_SUCCESS = "success";
	
	/**
	 * @var string
	 */
	protected $response;
	
	/**
	 * @var string
	 */
	protected $result = self::RESULT_UNKNOWN;
	
	protected $counts = [
		"tests" => 0,
		"assertions" => 0,
		"failures" => 0
	];
	
	/**
	 * Parse a response string
	 * @param string $response
	 */
	public function parseResponse($response)
	{
		$this->response = $response;
		$this->result = $result = call_user_func(function() use ($response) {
			if (preg_match("/FAILURES\!/", $response)) {
				return self::RESULT_ERROR;
			} else if (preg_match("/OK/", $response)) {
				return self::RESULT_SUCCESS;
			}

			return self::RESULT_UNKNOWN;
		});
		$this->counts = $this->_calcCounts();
	}
	
	/**
	 * Generate a summary of the result
	 * 
	 * @return string
	 */
	public function summary()
	{
		$parts = [];
		
		if ($this->counts["tests"] > 0) {
			$parts[] = $this->counts["tests"] ." tests";
		}
		
		if ($this->counts["assertions"] > 0) {
			$parts[] = $this->counts["assertions"] ." assertions";
		}
		
		if ($this->counts["failures"] > 0) {
			$parts[] = $this->counts["failures"] ." failures";
		}
		
		return implode(", ", $parts);
	}
	
	public function toArray() {
		return array_merge(parent::toArray(), [
			"summary" => $this->summary()
		]);
	}
	
	private function _calcCounts()
	{
		$counts = [];
		if ($this->result === self::RESULT_ERROR) {
			preg_match("/Tests: ([0-9]+), Assertions: ([0-9]+), Failures: ([0-9]+)/", $this->response, $matches);
			
			$counts = [
				"tests" => (int) $matches[1],
				"assertions" => (int) $matches[2],
				"failures" => (int) $matches[3]
			];
		} else if ($this->result === self::RESULT_SUCCESS) {
			preg_match("/([0-9]+) tests, ([0-9]+) assertions/", $this->response, $matches);
			
			$counts = [
				"tests" => (int) $matches[1],
				"assertions" => (int) $matches[2]
			];
		}
		
		return array_merge([
			"tests" => 0,
			"assertions" => 0,
			"failures" => 0
		], $counts);
	}
}