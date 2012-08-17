<?php

/* CreditCardFraudDetection.php
 *
 * Copyright (C) 2004 MaxMind LLC
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

require ("HTTPBase.php");
class CreditCardFraudDetection extends HTTPBase {
  function CreditCardFraudDetection() {
    $this->isSecure = 1;    // use HTTPS by default
    $this->num_allowed_fields = 8;

    //set the allowed_fields hash
    $this->allowed_fields["i"] = 1;
    $this->allowed_fields["domain"] = 1;
    $this->allowed_fields["city"] = 1;
    $this->allowed_fields["region"] = 1;
    $this->allowed_fields["postal"] = 1;
    $this->allowed_fields["country"] = 1;
    $this->allowed_fields["bin"] = 1;
    $this->allowed_fields["binName"] = 1;
    $this->allowed_fields["binPhone"] = 1;
    $this->allowed_fields["custPhone"] = 1;
    $this->allowed_fields["license_key"] = 1;

    //set the url of the web service
    $this->url = "app/ccv2r";
  }
}
?>