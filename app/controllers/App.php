<?php
/**
 * App controller class
 *
 * Handles requests to the application pages.
 *
 * Copyright 2011 Horde LLC (http://www.horde.org)
 *
 * @license  http://opensource.org/licenses/bsd-license.php BSD
 * @author Michael J Rubinsky <mrubinsk@horde.org>
 */
class HordeWeb_App_Controller extends HordeWeb_Controller_Base
{
    /**
     *
     *
     * @param Horde_Controller_Response $response
     */
    protected function _processRequest(Horde_Controller_Response $response)
    {
        switch ($this->_matchDict->action) {
        case 'index':
            $this->_index($response);
            break;
        case 'app':
            $this->_app($response);
            break;
        case 'authors':
            $this->_authors($response);
            break;
        case 'docs':
            $this->_docs($response);
            break;
        case 'screenshot':
            $this->_screenshot($response);
            break;
        case 'roadmap':
            $this->_roadmap($response);
            break;
        default:
            $this->_notFound($response);

        }
    }

    /**
     *
     */
    protected function _setup()
    {
        parent::_setup();
        $view = $this->getView();
        $view->addTemplatePath(
            array($GLOBALS['fs_base'] . '/app/views/App',
                  $GLOBALS['fs_base'] . '/app/views/App/apps/' . $this->_matchDict->app));
        $view->appname = $this->_matchDict->app;
        $view->hasAuthors = file_exists($GLOBALS['fs_base'] . '/app/views/App/apps/' . $this->_matchDict->app . '/appauthors.html.php');
        $view->hasScreenshots = file_exists($GLOBALS['fs_base'] . '/app/views/App/apps/' . $this->_matchDict->app . '/appscreenshots.html.php');
    }

    protected function _index(Horde_Controller_Response $response)
    {
        $view = $this->getView();
        // @TODO: Look this up in some kind of config/lookup array.
        $view->page_title = 'The Horde Project::' . ucfirst($this->_matchDict->app);
        $layout = $this->getInjector()->getInstance('Horde_Core_Ui_Layout');
        $layout->setView($view);
        $layout->setLayoutName('main');
        $response->setBody($layout->render('index'));
    }

    /**
     *
     * @param Horde_Controller_Response $response
     */
    protected function _app(Horde_Controller_Response $response)
    {
        $view = $this->getView();
        $view->page_title = 'The Horde Project::' . ucfirst($this->_matchDict->app);
        $layout = $this->getInjector()->getInstance('Horde_Core_Ui_Layout');
        $template = 'app';
        // Do we know about this app?
        if (file_exists($GLOBALS['fs_base'] . '/app/views/App/apps/' . urlencode($this->_matchDict->app)) === false) {
            $view->page_title = '404 Not Found';
            $template = '404';
        }
        $layout->setView($view);
        $layout->setLayoutName('main');
        $response->setBody($layout->render($template));
    }

    protected function _authors(Horde_Controller_Response $response)
    {
        $view = $this->getView();
        $view->addTemplatePath(array($GLOBALS['fs_base'] . '/app/views/shared/authors'));
        $view->page_title = 'The Horde Project::' . ucfirst($view->appname) . '::Authors';
        $layout = $this->getInjector()->getInstance('Horde_Core_Ui_Layout');
        $layout->setView($view);
        $layout->setLayoutName('main');
        $response->setBody($layout->render('authors'));
    }

    protected function _roadmap(Horde_Controller_Response $response)
    {
        $view = $this->getView();
        $view->page_title = 'The Horde Project::' . ucfirst($view->appname) . '::Roadmap';
        $layout = $this->getInjector()->getInstance('Horde_Core_Ui_Layout');
        $layout->setView($view);
        $layout->setLayoutName('main');
        $response->setBody($layout->render('roadmap'));
    }

    protected function _docs(Horde_Controller_Response $response)
    {
        $view = $this->getView();
        $view->page_title = 'The Horde Project::' . ucfirst($view->appname) . '::Docs';
        Horde::startBuffer();
        $file = Horde_Util::getFormData('f', 'docs.html');
        include $GLOBALS['fs_base'] . '/app/views/App/apps/' . $this->_matchDict->app . '/docs/' . $file;
        $view->content = Horde::endBuffer();
        $layout = $this->getInjector()->getInstance('Horde_Core_Ui_Layout');
        $layout->setView($view);
        $layout->setLayoutName('main');
        $response->setBody($layout->render('docs'));
    }

    protected function _screenshot(Horde_Controller_Response $response)
    {
        $view = $this->getView();
        // See if we have all parts
        // (only screenshots for now)
        $view->page_title = 'The Horde Project::' . ucfirst($view->appname) . '::Authors';
        $layout = $this->getInjector()->getInstance('Horde_Core_Ui_Layout');
        $layout->setView($view);
        $layout->setLayoutName('main');
        $response->setBody($layout->render('screenshots'));
    }

}
