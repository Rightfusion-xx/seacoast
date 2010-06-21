<?xml version="1.0" encoding="UTF-8" ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD Xhtml 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<!--
/**
 * osCommerce: XSLT Example for OSCFieldSuggest JS class
 *
 * File: includes/search_suggest.xsl
 * Version: 1.0
 * Date: 2007-03-28 17:49
 * Author: Timo Kiefer - timo.kiefer_(at)_kmcs.de
 * Organisation: KMCS - www.kmcs.de
 * Licence: General Public Licence 2.0
 */
-->

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output encoding="UTF-8" indent="no" method="html"  doctype-public="-//W3C//DTD Xhtml 1.0 Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<xsl:template match="/">
 <table width="300" height="200" style="opacity: 0.9; filter: alpha(opacity=90); background-color: white; border-collapse: collapse; border: 1px solid #CCCCCC; padding: 3px;">
 <xsl:for-each select="response/suggestlist/item">
  <tr onmouseover="myrow = this.getElementsByTagName('td').item(0); myrow.style.backgroundColor = '#000099'; myrow.style.color = '#FFFFFF';" onmouseout="myrow = this.getElementsByTagName('td').item(0); myrow.style.backgroundColor = '#FFFFFF'; myrow.style.color = '#000000';">
   <td onclick="window.location.href = '{url}';" style="cursor: pointer; border: 1px solid #CCCCCC; font-size: 8pt; font-family: Verdana;"><xsl:value-of select="name" /></td>
  </tr>
 </xsl:for-each>
 </table>
</xsl:template>
</xsl:stylesheet>