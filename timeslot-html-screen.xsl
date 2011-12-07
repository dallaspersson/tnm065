<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="*">
		<div class="body_content">
			<div class="resources">
				<h2>resources</h2>
				<xsl:apply-templates select="resources" />
			</div>
			<div class="calander-content">

				<div class="calander">

					<!-- Current month and year -->
					<h1 class="big-date">december 2011</h1>

					<!-- Current status -->
					<em>36 av 120 timmar bokade</em>

				</div>

				<div class="bookings">
					<h2>bookings</h2>
				</div>

			</div>
		</div>
	</xsl:template>



	<!-- Template match -->

	<xsl:template match="resources">
		<xsl:for-each select="resource">
	      <p><xsl:value-of select="resource-type"/></p>
	    </xsl:for-each>
  	</xsl:template>

  	<xsl:template match="resource">
		<xsl:apply-templates />
  	</xsl:template>
</xsl:stylesheet>