<?php

namespace Magestio\Instagram\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magestio\Instagram\Helper\Data as InstagramHelper;

/**
 * Class Instagram
 * @package Magestio\Instagram\Block
 */
class Instagram extends Template implements \Magento\Widget\Block\BlockInterface
{

    const REQUEST_TIMEOUT = 20;

    /**
     * @var InstagramHelper
     */
    protected $helper;

    public function __construct(
        Context $context,
        InstagramHelper $helper,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    public function enabled()
    {
        return $this->helper->isEnabled();
    }

    private function getApiUrl()
    {
        $accessToken = $this->helper->getAccessToken();
        $amount = $this->helper->getAmount();
        return "https://api.instagram.com/v1/users/self/media/recent/?access_token=" . $accessToken."&count=".$amount;
	}
	
	public function getTitle(){
		if($this->hasData("title")){
			$title = $this->getData("title");
		}else{
			$title = __('We are on Instagram');
		}
		return $title;
	}

	public function getAccount(){
		if($this->hasData("account")){
			$account = $this->getData("account");
		}else{
			$account = __('@igaccount');
		}
		return $account;
	}

    public function getPhotos()
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
