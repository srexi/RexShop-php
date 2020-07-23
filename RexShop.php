<?php

require_once('config.php');

class RexShop
{
    private $secret;
    private $apiKey;

    const REXSHOP_STATUS_COMPLETED = 'completed';
    const REXSHOP_STATUS_PENDING = 'waiting for payment';
    const REXSHOP_STATUS_REFUNDED = 'refunded';
    const REXSHOP_STATUS_DISPUTED = 'disputed';
    const REXSHOP_STATUS_DISPUTE_CANCELED = 'dispute canceled';
    const REXSHOP_STATUS_REVERSED = 'reversed';
    const SECONDS_PER_DAY = 86400;

    public function __construct()
    {
        $config = require_once('config.php');

        $this->secret = $config['secret'];
        $this->apiKey = $config['api_key'];

        if (!defined('TIME_NOW')) {
            define('TIME_NOW', time());
        }
    }

    /**
     * Fetches products.
     *
     * @return void
     */
    public function fetchProducts()
    {
        if (!isset($this->apiKey)) {
            return [];
        }

        $ch = curl_init("https://shop.rexdigital.group/api/v1/products?api_key={$this->apiKey}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result['products']['data'] ?? [];
    }

    /**
     * Checks if a transaction already exists
     *
     * @return void
     */
    public function transactionDuplicate()
    {
        //TODO: I recommend you implement logic to check if the transaction already exists
        //Make them unique by the transaction id and the transaction status. Or the signature.
        return false;
    }

    /**
     * Checks a given signature
     *
     * @param [array] $request
     * @return boolean
     */
    public function verifyWebhook($request)
    {
        return $request['RDG_WH_SIGNATURE'] === hash_hmac(
            'sha256',
            $request['order']['transaction_id'] . $request['status'],
            $this->secret
        );
    }
}
