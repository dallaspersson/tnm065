<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="*">
		<div class="body_content">
			<div class="resources">
				<xsl:apply-templates select="resources" />
			</div>
			<div class="calander">
			</div>
			<div class="bookings">
			</div>
		</div>
	</xsl:template>



	<!-- Template match -->

	<xsl:template match="resources">
		<xsl:for-each select="resource">
			<ul>
		      <li><xsl:value-of select="resource-type"/></li>
		    </ul>
	    </xsl:for-each>
  	</xsl:template>

  	<xsl:template match="resource">
		<xsl:apply-templates />
  	</xsl:template>
</xsl:stylesheet>