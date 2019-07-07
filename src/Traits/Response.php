<?php

namespace Nestpay\Traits;

use Illuminate\Http\Request;

trait Response
{
    public function response(array $postParams)
    {
        $request = $this->request($postParams);

        if (!$this->hashCheck($request)) {
            $text = 'digital_sign_verification_failed';
        } elseif (in_array(intval($request->input('mdStatus')), [1, 2, 3, 4])) {
            $text = $request->input('Response') === 'Approved' ? 'success' : $request->input('ErrMsg');
        } else {
            $text = $request->input('mdErrorMsg');
        }

        return ['order_id' => $request->input('oid'), 'result' => $request->all(), 'text' => $text];
    }

    protected function hashCheck(Request $request)
    {
        $hashparams = $request->input('HASHPARAMS');
        $hashparamsval = $request->input('HASHPARAMSVAL');
        $hashparam = $request->input('HASH');
        $paramsval = "";
        $index1 = 0;
        $index2 = 0;
        while ($index1 < strlen($hashparams)) {
            $index2 = strpos($hashparams, ":", $index1);
            $vl = $request->input(substr($hashparams, $index1, $index2 - $index1), '');
            $paramsval .= $vl;
            $index1 = $index2 + 1;
        }
        $hashval = $paramsval . $this->getStoreKey();
        $hash = base64_encode(pack('H*', sha1($hashval)));
        return ($paramsval != $hashparamsval || $hashparam != $hash) ? false : true;
    }
}
