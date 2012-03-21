<?php



  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");



  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {



?>



<!-- manufacturers //-->



          <tr>



            <td>



<?php



    require(DIR_WS_INCLUDES . '/boxes/boxTop.php');



    echo Categories;



	require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');

echo 
?>

<select name="categories_id"><option value="0" selected>Top</option><option value="287">My New Years Resolution</option><option value="291">&nbsp;&nbsp;&nbsp;Control my Sugar</option><option value="290">&nbsp;&nbsp;&nbsp;Detoxify</option><option value="296">&nbsp;&nbsp;&nbsp;Focus on Learning</option><option value="292">&nbsp;&nbsp;&nbsp;Improve my Memory</option><option value="297">&nbsp;&nbsp;&nbsp;Improve my Sleep</option><option value="289">&nbsp;&nbsp;&nbsp;Lose this Weight</option><option value="288">&nbsp;&nbsp;&nbsp;Quit Smoking</option><option value="295">&nbsp;&nbsp;&nbsp;Reduce my Cholesterol</option><option value="293">&nbsp;&nbsp;&nbsp;Reduce my Stress</option><option value="299">&nbsp;&nbsp;&nbsp;Relieve This Pain</option><option value="294">&nbsp;&nbsp;&nbsp;Strengthen my Heart</option><option value="298">&nbsp;&nbsp;&nbsp;Strengthen my Muscles</option><option value="83">Health Concerns</option><option value="110">&nbsp;&nbsp;&nbsp;Bowel Support</option><option value="307">&nbsp;&nbsp;&nbsp;Thyroid Health</option><option value="308">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hypothyroid</option><option value="41">&nbsp;&nbsp;&nbsp;Allergy &amp; Sinus Health</option><option value="304">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Food Allergies</option><option value="305">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hay Fever / Seasonal Allergies</option><option value="306">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Indoor Allergies</option><option value="97">&nbsp;&nbsp;&nbsp;Blood Circulation Health</option><option value="84">&nbsp;&nbsp;&nbsp;Blood Sugar Balance</option><option value="39">&nbsp;&nbsp;&nbsp;Brain and Memory Aid</option><option value="44">&nbsp;&nbsp;&nbsp;Cardiovascular Health</option><option value="99">&nbsp;&nbsp;&nbsp;Cholesterol Balance</option><option value="66">&nbsp;&nbsp;&nbsp;Digestive Support</option><option value="28">&nbsp;&nbsp;&nbsp;Immune System Support</option><option value="31">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cellular Balance</option><option value="30">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Immune Defense</option><option value="42">&nbsp;&nbsp;&nbsp;Joint, Bone &amp; Muscle Support</option><option value="113">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bone Support</option><option value="121">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Joint Support</option><option value="112">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Muscle Support</option><option value="95">&nbsp;&nbsp;&nbsp;Learning Aid</option><option value="47">&nbsp;&nbsp;&nbsp;Liver Health</option><option value="38">&nbsp;&nbsp;&nbsp;Mood Support</option><option value="100">&nbsp;&nbsp;&nbsp;Sleep Aid</option><option value="221">&nbsp;&nbsp;&nbsp;Smoking</option><option value="23">Mens Health</option><option value="87">&nbsp;&nbsp;&nbsp;Prostate Health</option><option value="303">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Benign Prostatic Hyperplasia</option><option value="302">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prostate Cancer</option><option value="301">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prostatitis</option><option value="24">&nbsp;&nbsp;&nbsp;Sexual Health</option><option value="85">&nbsp;&nbsp;&nbsp;Body Care</option><option value="233">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Massage &amp; Essential Oil</option><option value="239">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aromatherapy</option><option value="240">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Essential Oils</option><option value="241">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fragrence Oils</option><option value="242">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Massage Oils</option><option value="62">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bath &amp; Shower</option><option value="80">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cosmetics</option><option value="52">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gels, Creams &amp; Ointments</option><option value="49">Womens Health</option><option value="89">&nbsp;&nbsp;&nbsp;Menopause &amp; Hormonal Balance</option><option value="90">&nbsp;&nbsp;&nbsp;Menstruation</option><option value="93">&nbsp;&nbsp;&nbsp;Osteoporosis &amp; Bone Health</option><option value="94">&nbsp;&nbsp;&nbsp;Breast Health</option><option value="91">&nbsp;&nbsp;&nbsp;Fertility &amp; Sexual Health</option><option value="92">&nbsp;&nbsp;&nbsp;Health &amp; Beauty</option><option value="227">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bath &amp; Shower</option><option value="230">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Body Care</option><option value="228">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cosmetics</option><option value="229">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gels, Creams &amp; Ointments</option><option value="234">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Massage &amp; Essential Oil</option><option value="235">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aromatherapy</option><option value="236">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Essential Oils</option><option value="237">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fragrence Oils</option><option value="238">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Massage Oils</option><option value="22">Diet</option><option value="232">&nbsp;&nbsp;&nbsp;Detoxification</option><option value="231">&nbsp;&nbsp;&nbsp;Weight Loss</option><option value="309">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Green Teas and Extracts</option><option value="63">Vitamin Supplements</option><option value="226">&nbsp;&nbsp;&nbsp;Vitamins</option><option value="67">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A, D &amp; K Vitamins</option><option value="50">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amino Acids</option><option value="64">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;B Vitamins &amp; Complexes</option><option value="43">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C Vitamins &amp; Bioflavonoids</option><option value="68">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Carotenoids</option><option value="48">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E Vitamins &amp; Tocotrienols</option><option value="88">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lipotropics(Fat Burners)</option><option value="76">&nbsp;&nbsp;&nbsp;Colostrum Supplements</option><option value="37">&nbsp;&nbsp;&nbsp;Coral Calcium</option><option value="75">&nbsp;&nbsp;&nbsp;Garlic Supplements</option><option value="25">&nbsp;&nbsp;&nbsp;Green Super Foods</option><option value="29">&nbsp;&nbsp;&nbsp;Medicinal Mushrooms</option><option value="40">&nbsp;&nbsp;&nbsp;Multiple Vitamins</option><option value="46">&nbsp;&nbsp;&nbsp;Nutritional Drink Mixes</option><option value="65">&nbsp;&nbsp;&nbsp;Probiotics</option><option value="53">&nbsp;&nbsp;&nbsp;Minerals</option><option value="36">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Calcium Products</option><option value="55">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Chromium Products</option><option value="56">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Iron Products</option><option value="57">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Magnesium Products</option><option value="61">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Multiple Minerals</option><option value="54">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Potassium Products</option><option value="58">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selenium Products</option><option value="60">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trace Minerals</option><option value="59">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Zinc Products</option><option value="79">&nbsp;&nbsp;&nbsp;Specialty Supplements</option><option value="26">&nbsp;&nbsp;&nbsp;Proteolytic Enzymes</option><option value="120">&nbsp;&nbsp;&nbsp;Body Building</option><option value="74">Herbs</option><option value="77">&nbsp;&nbsp;&nbsp;Herbal Extracts</option><option value="51">&nbsp;&nbsp;&nbsp;Herbal Formulas</option><option value="78">&nbsp;&nbsp;&nbsp;Whole Herbs</option><option value="81">&nbsp;&nbsp;&nbsp;Chinese Herbs</option><option value="69">Essential Fats &amp; Fish Oils</option><option value="32">Homeopathic Remedies</option><option value="35">&nbsp;&nbsp;&nbsp;Gels &amp; Creams</option><option value="33">&nbsp;&nbsp;&nbsp;Homeopathic Blends</option><option value="34">&nbsp;&nbsp;&nbsp;Homeopathic Singles</option><option value="245">Teas, Books, Candles...</option><option value="257">&nbsp;&nbsp;&nbsp;Air Sanitizers</option><option value="247">&nbsp;&nbsp;&nbsp;Books</option><option value="248">&nbsp;&nbsp;&nbsp;Candles</option><option value="250">&nbsp;&nbsp;&nbsp;Gift Certifecate</option><option value="255">&nbsp;&nbsp;&nbsp;Nutrition Bars</option><option value="256">&nbsp;&nbsp;&nbsp;Other Foods</option><option value="254">&nbsp;&nbsp;&nbsp;Teas</option></select>

<?php

    require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');



?>



            </td>



          </tr>
<?php



  }



?>
