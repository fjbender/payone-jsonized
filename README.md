## JSONize the Payone API

Payone normally uses a key-value HTTP POST mode of communication. This piece of software aims to provide a JSON mapper to the Payone API. It is still in a very early stage.

## Usage

Clone the repository and install all dependencies using composer:

    $ git clone https://github.com/fjbender/payone-jsonized
    $ cd payone-jsonized
    $ composer install

After that you can use either [configure your webserver to use the public/index.php script to handle stuff](http://www.slimframework.com/docs/start/web-servers.html) or use the PHP built-in webserver to test the JSON interface locally:

    $ cd public/
    $ php -S localhost:8888 

Then you can POST Payone API requests against the `/request/` resource as JSON and will receive a JSON response:

    $ curl -s -H "Content-Type: application/json" -X POST -d '{"mode":"test", "request":"preauthorization"}' http://localhost:8888/request/
    {"status":"ERROR","errorcode":"1203","errormessage":"Parameter {mid} faulty or missing","customermessage":"An error occured while processing this transaction (wrong parameters)."}


## Legal notice

Please note that this is no official Payone software and no support can and will be given.

