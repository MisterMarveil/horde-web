<?php
/**
 * Screenshots controller for the (as yet non-existent) consolidated download
 * page.
 *
 * Handles requests to the download page.
 *
 * Copyright 2011 Horde LLC (http://www.horde.org)
 *
 * @license  http://opensource.org/licenses/bsd-license.php BSD
 * @author Michael J Rubinsky <mrubinsk@horde.org>
 */
class HordeWeb_Screenshot_Controller extends HordeWeb_Controller_Base
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
        default:
            $this->_notFound($response);
            return;
        }
    }

    protected function _setup()
    {
        parent::_setup();
        $view = $this->getView();
        $view->addTemplatePath(
            array($GLOBALS['fs_base'] . '/app/views/Screenshot'));
        $view->stable = HordeWeb_Utils::getStableApps();
    }

    /**
     *
     * @param Horde_Controller_Response $response
     */
    protected function _index(Horde_Controller_Response $response)
    {
    }

    protected function _app(Horde_Controller_Response $response)
    {
    }

}
