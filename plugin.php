<?php

require __DIR__ . '/vendor/paypal/rest-api-sdk-php/sample/invoice/CreateInvoice.php';

class paypal_slack extends SlackServicePlugin
{

    public $name = " PayPal Slack";
    public $desc = "Get payment notifications, and request money securely.";
    public $tooltip = "Connect your PayPal account to your slack group so you can manage your PayPal Account securely";

    public $cfg = array(
        'has_token' => true,
    );

    function onInit()
    {

        $channels = $this->getChannelsList();
        foreach ($channels as $k => $v) {
            if ($v == '#general') {
                $this->icfg['channel'] = $k;
                $this->icfg['channel_name'] = $v;
            }
        }

        $this->icfg['branch'] = '';
        $this->icfg['botname'] = 'PayPalBot';
    }

    function onView()
    {

        return $this->smarty->fetch('view.txt');
    }

    function onEdit()
    {

        $channels = $this->getChannelsList();

        if ($_GET['save']) {

            $this->icfg['sales_channel'] = $_POST['sales_channel'];
            $this->icfg['sales_channel_name'] = $channels[$_POST['sales_channel']];
            $this->icfg['shipping_channel'] = $_POST['shipping_channel'];
            $this->icfg['shipping_channel_name'] = $channels[$_POST['shipping_channel']];
            $this->icfg['pp_act'] = $_POST['pp_act'];
            $this->icfg['pp_cid'] = $_POST['pp_cid'];
            $this->icfg['pp_pwd'] = $_POST['pp_pwd'];
            $this->icfg['botname'] = $_POST['botname'];
            $this->saveConfig();

            header("location: {$this->getViewUrl()}&saved=1");
            exit;
        }

        $this->smarty->assign('channels', $channels);

        return $this->smarty->fetch('edit.txt');
    }


    //Where the incoming magic happens!
    function onHook($req)
    {

        $in = $req['post'];
        if (!$this->icfg['sales_channel']) {
            return array(
                'ok' => false,
                'error' => "No channel configured",
            );
        }

        //Slack incoming command
        if (!is_null($in['user_name'])) {

            $requester = $in['user_name'];
            $invoice['from'] = $this->icfg['pp_act'];
            $text = explode(' ', $in['text']);
            $command = $text[0];
            $invoice['to'] = $text[1];
            $invoice['amount'] = $text[2];

            //All remaining elements make up the invoice
            $note = "";
            for ($i = 3; $i < sizeof($text); $i++) {
                $note = $note . ' ' . $text[$i];
            }

            $invoice['note'] = $note;

            switch ($command) {
                case "invoice" : {
                    $this->sendMessage("Hi " . $requester . ". I'm going to request $" . $invoice['amount'] . " from " . $invoice['to'] . " for:\n" . $note,
                        $this->icfg['sales_channel']);
                    $createdInvoice = createInvoice($invoice, $this->icfg['pp_cid'], $this->icfg['pp_pwd']);
                    $this->sendMessage("Invoice #" . $createdInvoice->getId() . " Created and Sent",
                        $this->icfg['sales_channel']);

                    //$this->sendMessage());
                    return array(
                        'ok' => true,
                        'status' => "Nothing found to report",
                    );
                } break;
                case "support" : {
                    $this->sendMessage("Hi " . $requester . ". We are here to help you 24/7!!! :ambulance:\n" .
                        "> If you need to reach a human, please call: `1 (888) 221-1161`\n".
                        "> If you need file a ticket visit: `https://www.paypal.com/mts`\n".
                        "You can also give us a shout out on Twitter @PayPal",
                        $this->icfg['sales_channel']);

                } break;
                case "help" :
                default :
                {

                    $this->sendMessage(
                        "Welcome to the PayPal Bot. Here's what I can help you with so far:\n".
                        "*invoice*: Sends a request for money. Use: `invoice receiver@email.com amount note`\n".
                        "*support*: Shows you all the ways you can contact us when you need help\n".
                        "*help*: Shows this helpful menu`\n",
                    $this->icfg['sales_channel']);

                }
            }
        } else {

            $sale = ":moneybag:Ka-Ching!:moneybag: You just made a sale!\n" .
                "You sold " . $in['item_name1'] . " for " . $in['mc_currency'] . $in['mc_gross'] . "\n" .
                "Shipping has been notified. \n";

            $shipping =  "Please dispatch ".$in['item_name1'] ." to \n" .
                "```\n" .
                $in['address_name'] . "\n" .
                $in['address_street'] . "\n" .
                $in['address_city'] . ", " . $in['address_state'] . " " . $in['address_zip'] . "\n" .
                $in['address_country'] . "\n```";

            $this->sendMessage($sale,$this->icfg['sales_channel']);
            $this->sendMessage($shipping,$this->icfg['shipping_channel']);

            return array(
                'ok' => true,
                'status' => "Nothing found to report",
            );
        }
    }

    function getLabel()
    {
        return "Post notification to {$this->icfg['channel_name']} as {$this->icfg['botname']}";
    }

    private function sendMessage($text,$channel)
    {

        $ret = $this->postToChannel($text, array(
            'channel' => $channel,
            'username' => $this->icfg['botname'],
        ));

        return array(
            'ok' => true,
            'status' => "Sent a message",
        );
    }
}
