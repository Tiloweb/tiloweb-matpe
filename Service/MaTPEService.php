<?php
/**
 * Created by PhpStorm.
 * User: tilotiti
 * Date: 20/02/2017
 * Time: 17:35
 */

namespace Tiloweb\MaTPEBundle\Service;

class MaTPEService
{
    private $login;
    private $key;
    private $firm;

    const API_URL = 'https://www.facturation.pro/firms/';

    public function __construct($login, $key, $firm)
    {
        $this->login = $login;
        $this->key = $key;
        $this->firm = $firm;
    }

    private function request($method, $endpoint, $data = array()) {


        switch($method) {
            case "POST":
            case "PATCH":
            case "DELETE":
                $curl = curl_init(self::API_URL.$this->firm.$endpoint.'.json');

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case "GET":
            default:
                $curl = curl_init(self::API_URL.$this->firm.$endpoint.'.json'.'?'.http_build_query($data));

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                break;
        }

        curl_setopt($curl, CURLOPT_USERPWD, $this->login . ":" . $this->key);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'User-Agent: Fidcar.com (thibault@fidcar.com)',
            'Content-type: application/json; charset=utf-8'
        ));

        $response = curl_exec($curl);

        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if($response) {
            $req_json = json_decode($response, true);

            if(isset($req_json['errors'])) {
                 foreach($req_json['errors'] as $error => $message) {
                    throw new \Exception($error." : ".$message[0]." ".self::API_URL.$this->firm.$endpoint.'.json'.'?'.http_build_query($data));
                }
            } else {
                return $req_json;
            }
        } else {
            if($http_code != 200) {
                throw new \Exception("Empty response ($http_code) : ".$method." ".self::API_URL.$this->firm.$endpoint.'.json'.'?'.http_build_query($data));
            }
        }

    }

    /*
     * Customer
     */

    /**
     * List MaTPE Customers
     * @return array
     */
    public function listCustomers() {
        return $this->request('GET', '/customers');
    }

    /**
     * Create a new customer on MaTPE
     * @param array $data
     * @return array
     */
    public function createCustomer(array $data) {
        return $this->request('POST', '/customers', $data);
    }

    /**
     * Get a customer from MaTPE
     * @param $id
     * @return array
     */
    public function getCustomer($id) {
        return $this->request('GET', '/customers/'.$id);
    }

    /**
     * Update a customer from MaTPE
     * @param $id
     * @param array $data
     * @return array
     */
    public function updateCustomer($id, array $data) {
        $this->request('PATCH', '/customers/'.$id, $data);

        return $this->getCustomer($id);
    }

    /*
     * Invoices
     */

    /**
     * List all invoices on MaTPE
     * @param $customer integer
     * @return array
     */
    public function listInvoices($customer = 0) {
        if($customer) {
            return $this->request('GET', '/invoices', array(
                'customer_id' => $customer
            ));
        } else {
            return $this->request('GET', '/invoices');
        }
    }

    /**
     * Create an invoice in Quipu
     * @param $customer
     * @param array $data
     * @param array $items
     * @return array
     */
    public function createInvoice($customer, array $data, array $items) {
        $data['customer_id'] = $customer;
        $data['items'] = $items;

        return $this->request('POST', '/invoices', $data);
    }

    /**
     * Get an Invoice on MaTPE
     * @param $id int
     * @return array
     */
    public function getInvoice($id) {
        return $this->request('GET', '/invoices/'.$id);
    }

    /**
     * Update attributes invoice on MaTPE
     * @param $id
     * @param array $data
     * @return array
     */
    public function updateInvoice($id, array $data) {
        $this->request('PATCH', '/invoices/'.$id, $data);

        return $this->getInvoice($id);
    }
}