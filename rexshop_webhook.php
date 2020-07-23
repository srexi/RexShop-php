<?php

require_once('RexShop.php');

if (!isset($_POST)) {
    return rexshopOnFailure();
}

$RexShop = new RexShop;

$request = json_decode(file_get_contents("php://input"), true);

if (!$RexShop->verifyWebhook($request)) {
    return rexshopOnSuccess();
}

if ($RexShop->transactionDuplicate($request)) {
    return rexshopOnSuccess();
}

switch (strtolower($request['status'])) {
    case RexShop::REXSHOP_STATUS_COMPLETED:
        return handleCompletedTransaction($request);
    case RexShop::REXSHOP_STATUS_PENDING:
        return handlePendingTransaction($request);
    case RexShop::REXSHOP_STATUS_REFUNDED:
        return handleRefundedTransaction($request);
    case RexShop::REXSHOP_STATUS_DISPUTE_CANCELED:
        return handleDisputeCanceledTransaction($request);
    case RexShop::REXSHOP_STATUS_DISPUTED:
        return handleDisputedTransaction($request);
    case RexShop::REXSHOP_STATUS_REVERSED:
        return handleReversedTransaction($request);
    default:
        return rexshopOnFailure();
        break;
}

/**
 * Handles a completed transaction.
 * This is a fully completed and paid for transaction.
 *
 * @param [array] $request
 * @return httpresponse
 */
function handleCompletedTransaction($request)
{
    //TODO: Implement your custom logic here.

    return rexshopOnSuccess();
}

/**
 * Handles a pending transaction (funds are being processed by the payment processor).
 * It's recommended not to ship or deliver the product at this stage.
 * Once the payment processor has processed the transaction another webhook will be sent.
 *
 * @param [array] $request
 * @return httpresponse
 */
function handlePendingTransaction($request)
{
    //TODO: Implement your custom logic here.

    return rexshopOnSuccess();
}

/**
 * Handles a refunded transaction (you voluntarily refunded money to the customer).
 *
 * @param [array] $request
 * @return httpresponse
 */
function handleRefundedTransaction($request)
{
    //TODO: Implement your custom logic here.

    return rexshopOnSuccess();
}

/**
 * Handles a canceled disputed transaction (you won a dispute or the customer voluntarily closed it).
 *
 * @param [array] $request
 * @return httpresponse
 */
function handleDisputeCanceledTransaction($request)
{
    //TODO: Implement your custom logic here.

    return rexshopOnSuccess();
}

/**
 * Handles a disputed transaction. A customer has reported the transaction as fraudulent
 * or that they did not receive the product or the product was not in the expected condition.
 *
 * @param [array] $request
 * @return httpresponse
 */
function handleDisputedTransaction($request)
{
    //TODO: Implement your custom logic here.

    return rexshopOnSuccess();
}

/**
 * Handles a reversed transaction (you lost the dispute).
 * the payment processor has decided to transfer to disputed funds back the customer.
 *
 * @param [array] $request
 * @return httpresponse
 */
function handleReversedTransaction($request)
{
    //TODO: Implement your custom logic here.

    return rexshopOnSuccess();
}

/**
 * Return a 200 ok response
 *
 * @return httpresponse
 */
function rexshopOnSuccess()
{
    header("Status: 200 OK", 200);
    exit;
}

/**
 * Return a generic error code
 *
 * @return httpresponse
 */
function rexshopOnFailure()
{
    header('Status: 400 Bad Request', 400);
    exit;
}
