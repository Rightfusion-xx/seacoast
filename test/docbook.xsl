<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

 <xsl:output method="html"/>

 <xsl:template match="article">
   <html>
   <head>
     <title><xsl:value-of select="title"/></title>
   </head>
   <body>
     <h1><xsl:value-of select="title"/></h1>
     <xsl:apply-templates select="section"/>
   </body>
   </html>
 </xsl:template>

 <xsl:template match="section">
   <xsl:apply-templates/>
   <hr/>
 </xsl:template>

 <xsl:template match="section/title">
   <h2><xsl:apply-templates/></h2>
 </xsl:template>

 <xsl:template match="para">
   <p><xsl:apply-templates/></p>
 </xsl:template>

 <xsl:template match="itemizedlist">
   <ul><xsl:apply-templates/></ul>
 </xsl:template>

 <xsl:template match="listitem">
   <li><xsl:apply-templates/></li>
 </xsl:template>
</xsl:stylesheet>