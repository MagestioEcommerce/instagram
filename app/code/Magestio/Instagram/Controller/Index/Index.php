<?php

namespace Magestio\Instagram\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magestio\Instagram\Helper\Data as InstagramHelper;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Index
 * @package Magestio\Instagram\Controller\Index
 */
class Index extends Action implements HttpGetActionInterface
{

    const REQUEST_TIMEOUT = 20;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var InstagramHelper
     */
    protected $helper;

    /**
     * Index constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param InstagramHelper $helper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        InstagramHelper $helper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $result = $this->resultJsonFactory->create();
            $photos = $this->getPhotos();
            return $result->setData($photos);
        }
    }

    private function getApiUrl()
    {
        $accessToken = $this->helper->getAccessToken();
        $amount = $this->helper->getAmount();
        return "https://api.instagram.com/v1/users/self/media/recent/?access_token=" . $accessToken."&count=".$amount;
    }

    private function getPhotos()
    {
        $request = new \Zend_Http_Client($this->getApiUrl(), array('timeout' => self::REQUEST_TIMEOUT));
        $request->setMethod(\Zend_Http_Client::GET);
        $response = json_decode($request->request()->getBody());
        $images = [];
        if (!isset($response->code) and $response->meta->code == 200) {
            foreach ($response->data as $post) {
                $images[] = [
                    'url' => $post->link,
                    'image' => $post->images->low_resolution->url
                ];
            }
        }

        return $images;
    }

}