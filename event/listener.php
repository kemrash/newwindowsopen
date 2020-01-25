<?php

namespace kemrash\newwindowsopen\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
/**
* Assign functions defined in this class to event listeners in the core
*
* @return array
* @static
* @access public
*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.text_formatter_s9e_configure_after'	=> 'configure_textformatter',
		);
	}

	/**
	* Constructor
	*/

	public function configure_textformatter($event)
	{
		/** @var \s9e\TextFormatter\Configurator $configurator */
		$configurator = $event['configurator'];

		// the default URL tag template is this:
		// <a href="{@url}" class="postlink"><xsl:apply-templates/></a>
		$default_url_template = $configurator->tags['URL']->template;

		$url_template_new_window = str_replace(
			'href="{@url}"',
			'href="{@url}" target="_blank" rel="nofollow"',
			$default_url_template
		);

		// select the appropriate template based on the parameters and the URL
		$configurator->tags['URL']->template =
			'<xsl:choose>' .
				'<xsl:when test="not(starts-with(@url, \'' . generate_board_url() . '\'))">' . $url_template_new_window . '</xsl:when>' .
				'<xsl:otherwise>' . $default_url_template . '</xsl:otherwise>' .
			'</xsl:choose>';
	}
}