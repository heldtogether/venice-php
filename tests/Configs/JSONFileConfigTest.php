<?php namespace Tests\Configs;

use Tests\TestCase;

class JSONFileConfigTest extends TestCase {


	/**
	 * Can create Config
	 *
	 * @return void
	 */
	function testCanCreateConfig() {

		$factory = \Mockery::mock('\Slab\Features\Factory');
		$filesystem = \Mockery::mock('League\Flysystem\Filesystem');

		$config = new \Slab\Features\Configs\JSONFileConfig(
			$factory,
			$filesystem
		);

		$this->assertInstanceOf(
			'Slab\Features\Interfaces\ConfigInterface',
			$config
		);

	}


	/**
	 * Can get features from Config
	 *
	 * @return void
	 */
	public function testCanGetFeaturesFromConfig() {

		$filename = '/test/filename.json';

		$rules = array(
			'feature-1' => true,
			'feature-2' => array(
				'type' => 'timed',
			),
		);

		$factory = \Mockery::mock('\Slab\Features\Factory');
		$factory->shouldReceive('create')
		        ->with($rules['feature-1']);
		$factory->shouldReceive('create')
		        ->with($rules['feature-2']);

		$filesystem = \Mockery::mock('League\Flysystem\Filesystem');
		$filesystem->shouldReceive('read')
		           ->once()
		           ->with($filename)
		           ->andReturn(json_encode($rules));

		$config = new \Slab\Features\Configs\JSONFileConfig(
			$factory,
			$filesystem
		);
		$config->setFilename($filename);

		$config->rules();

	}


}
