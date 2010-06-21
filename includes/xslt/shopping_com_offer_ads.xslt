
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:sc="urn:types.partner.api.shopping.com" version="1.0">

<xsl:output method="html"/>

<xsl:template match="/">
	
	<xsl:for-each select="sc:GeneralSearchResponse/sc:categories/sc:category/sc:items/sc:product/sc:offers/sc:offer">
		<div style="height:10em;width:45%;float:left;padding:5px;border:solid 1px #3333CC;margin-bottom:20px;margin-left:5px;margin-right:5px;">
                <a href="{sc:offerURL}" rel="nofollow" target="_blank"><img src="{sc:imageList/sc:image/sc:sourceURL}" alt="{sc:name}" title="{sc:name}" border="0" style="margin:5px;width:100px;height:100px;float:left;"/></a>
                <b><xsl:value-of select="sc:store/sc:name"/></b><br/><a href="{sc:offerURL}" rel="nofollow" target="_blank"><xsl:value-of select="sc:name"/></a><br/><span style="color:#00CC00;font-weight:bold;">$<xsl:value-of select="sc:basePrice"/></span><br/><xsl:value-of select="sc:stockStatus"/>
                <br style="clear:both;"/></div>
	</xsl:for-each>
</xsl:template> 

<xsl:template match="offer">
	<div style="border:solid 1px #3333CC;margin-top:20px;margin-left:5px;margin-right:5px;">
                <a href="{offerurl}" rel="nofollow" target="_blank"><img src="{imagelist/image/sourceurl}" alt="{name}" title="{name}" border="0" style="margin:5px;width:100px;height:100px;float:left;"/></a>
                <b>{store/name}</b><br/><a href="{offerurl}" rel="nofollow" target="_blank">{name}</a><br/><span style="color:#00CC00;font-weight:bold;">${baseprice}</span><br/>{stockstatus}
                <br style="clear:both;"/>    	</div>
</xsl:template>

<xsl:template match="placeholder">
	
</xsl:template>

<xsl:template match="@*|node()">
	
	<b>killed the rest</b>
</xsl:template>
</xsl:stylesheet>


<!-- *** END OF STYLESHEET *** -->
