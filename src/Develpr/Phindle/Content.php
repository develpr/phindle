<?php namespace Develpr\Phindle;

/**
 * This is a concrete implementation of the ContentInterface for use when creating Phindle files. The implementation
 * is should provide all functionality required to create a Phindle file with multiple content areas.
 *
 * Class Content
 * @package Develpr\Phindle
 */
class Content implements ContentInterface{

	private $html;
	private $sections;
	private $title;
    private $position;
	private $uniqueIdentifier;
    private $htmlHelper;
    private $staticResourcePath;

	function __construct($htmlElementExtractor = false)
	{
        $this->htmlParser = $htmlElementExtractor;

		//We will use this unique identifier for the static resources that we'll need to generate
		$this->uniqueIdentifier = round(time()/2) . "" . rand(11111, 99999);
	}

    /**
     * A html parser
     *
     * @param HtmlElementExtractor $htmlParser
     * @return $this
     */
    public function setHtmlParser(HtmlElementExtractor $htmlParser)
    {
        $this->htmlParser = $htmlParser;

        return $this;
    }

    /**
     * Allows you to specify a static resource path to be appended to <img> and <link> tags
     *
     * @param string $shouldAppend
     * @return $this
     */
    public function setStaticResourcePath($path)
    {
        $this->staticResourcePath = $this->addTrailingSlash($path);

        return $this;
    }

    /**
     * Returns a unique Identifier
     * @return string
     */
    public function getUniqueIdentifier()
    {
       return $this->uniqueIdentifier;
    }

    public function setUniqueIdentifier($uniqueIdentifier)
    {
        $this->uniqueIdentifier = $uniqueIdentifier;

		return $this;
    }

    /**
	 * Set the HTML that will be output
	 *
	 * @param mixed $html
	 */
	public function setHtml($html)
	{
		$this->html = $html;

		return $this;
	}

	/**
	 * Set the sections that exist within this content object
	 * @param mixed $sections
	 */
	public function setSections($sections)
	{
		$this->sections = $sections;

		return $this;
	}

	/**
	 * Set the title of this object
	 * @param mixed $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}



	/**
	 * Echo the body of the content as HTML
	 */
	public function getHtml()
	{
		return $this->html;
	}

	/**
	 * Get the title of this content object
	 *
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Returns an anchor tag to the content object for use when creating links.
	 *
	 * @param string $id optionally provide the id (id="chapter_1_1") which will be added to anchor tag
	 * @return string
	 */
	public function getAnchorPath($id = "")
	{
		return strlen($id > 0) ? $this->uniqueIdentifier . '.html#' . $id : $this->uniqueIdentifier . '.html';
	}

	/**
	 * Provides the relative position of the content. This is for sorting purposes and is what will determine
	 * where each content object ends up in the final ebook.
	 *
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

    public function setPosition($position)
    {
        $this->position = $position;

		return $this;
    }

	/**
	 * note: we're using a "manual" approach here because the other options are
	 * 1) require a user nest ContentInterface objects within each other, which increases the complexity
	 * 2) parse the html for this ContentInterface's toHtml result and figure out where the ids are, which is not
	 *    possible without coming up with a convention which again creates extra work/learning on users part
	 *
	 * Returns a mapping of position => section data, where section data contains
	 *    sectionTitle        => 1.1 Detail Point
	 *  id                    => blah_11
	 *  content (optional)    => array( sectionTitle => ... id => ... content => array( ... ) )
	 * @return mixed
	 */
	public function getSections()
	{
		return $this->sections;
	}

    //todo: this isn't dry, it's a copy/paste from the xmlrenderer
    /**
     * Removing a leading slash from a path
     * @param $path
     * @return mixed
     */
    protected function removeLeadingSlash($path)
    {
        return (substr($path, 0, 1) == '/' ? substr_replace($path, "", 0, 1) : $path);
    }

    /**
     * Add a trailing slash to a path
     *
     * @param $path
     * @return string
     */
    protected function addTrailingSlash($path)
    {
        return (substr($path, -1) == '/' ? $path : $path . '/');
    }
}