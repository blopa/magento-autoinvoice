<?php
class Werules_Autoinvoice_Model_Observer
{
	public function autoInvoice($observer) {
		$shipment = $observer->getEvent()->getShipment();
		$order = $shipment->getOrder();
		if($order->canInvoice())
		{
			$invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
			$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
			$invoice->register();
			$transactionSave = Mage::getModel('core/resource_transaction')
			->addObject($invoice)
			->addObject($invoice->getOrder());
			$transactionSave->save();
		}
	}
}