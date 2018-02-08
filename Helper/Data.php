<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_SocialLogin
 * @copyright   Copyright (c) 2016 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\SocialLogin\Helper;

use Magento\Framework\App\RequestInterface;
use Mageplaza\Core\Helper\AbstractData as CoreHelper;

/**
 * Class Data
 *
 * @package Mageplaza\SocialLogin\Helper
 */
class Data extends CoreHelper
{
    const CONFIG_MODULE_PATH = 'sociallogin';

    /**
     * @param null $storeId
     * @return mixed
     */
    public function isCaptchaEnabled($storeId = null)
    {
        return $this->getConfigValue(static::CONFIG_MODULE_PATH . '/captcha/is_enabled', $storeId);
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param                                         $formId
     * @return string
     */
    public function captchaResolve(RequestInterface $request, $formId)
    {
        $captchaParams = $request->getPost(\Magento\Captcha\Helper\Data::INPUT_NAME_FIELD_VALUE);

        return isset($captchaParams[$formId]) ? $captchaParams[$formId] : '';
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function canSendPassword($storeId = null)
    {
        return $this->getConfigGeneral('send_password', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getPopupEffect($storeId = null)
    {
        return $this->getConfigGeneral('popup_effect', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getStyleManagement($storeId = null)
    {
        $style = $this->getConfigGeneral('style_management', $storeId);
        if ($style == 'custom') {
            return $this->getCustomColor($storeId);
        }

        return $style;
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getCustomColor($storeId = null)
    {
        return $this->getConfigGeneral('custom_color', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getCustomCss($storeId = null)
    {
        return $this->getConfigGeneral('custom_css', $storeId);
    }

    /**
     * @return mixed
     */
    public function isSecure()
    {
        $isSecure = $this->getConfigValue('web/secure/use_in_frontend');

        return $isSecure;
    }
    /**
     * @return bool
     */
    public function isEnableSocialLoginPro()
    {
        return $this->isModuleOutputEnabled('Mageplaza_SocialLoginPro');
    }
    /**
     * @return bool
     */
    public function isGoogleCaptcha($storeId = null){
        if($this->isEnableSocialLoginPro()){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $captcha = $objectManager->create('Mageplaza\SocialLoginPro\Model\Config\Source\Captcha');
            $enabled = $this->getConfigGeneral('captcha/enabled', $storeId);
            if ($enabled == $captcha::TYPE_RECAPTCHA) {
                return true;
            } elseif ($enabled == $captcha::TYPE_NO){
                return true;
            }
            return false;
        }
        return false;
    }
    public function isInvisibleCaptcha($storeId = null){
        $result = [];
        if($this->isEnableSocialLoginPro()){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $captchaType = $objectManager->create('Mageplaza\SocialLoginPro\Model\Config\Source\RecaptchaType');
            $type = $this->getConfigGeneral('captcha/recaptcha_type', $storeId);
            if($type == $captchaType::TYPE_INVISIBLE){
                $forms = explode(',', $this->getConfigGeneral('captcha/recaptcha_forms', $storeId));
                foreach ($forms as $key => $value){
                    if($value == 'user_login'){
                        $result['user_login'] = true;
                    }
                    if($value == 'user_create'){
                        $result['user_create'] = true;
                    }
                    if($value == 'user_forgotpassword'){
                        $result['user_forgotpassword'] = true;
                    }
                }
            }
        }
        return array_keys($result);
    }
}
