<?php namespace Pensoft\Tdwgform;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
		return [
			'Pensoft\Tdwgform\Components\Form' => 'TDWGForm',
		];
    }

    public function registerSettings()
    {
    }

	public $require = ['Pensoft.Calendar', 'Rainlab.Location', 'Rainlab.User', 'Multiwebinc.Recaptcha'];

	/**
	 * Returns information about this plugin.
	 *
	 * @return array
	 */
	public function pluginDetails()
	{
		return [
			'name'        => 'TDWGForm',
			'description' => 'No description provided yet...',
			'author'      => 'Pensoft',
			'icon'        => 'icon-building'
		];
	}
}
