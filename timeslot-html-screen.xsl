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
					<em>current status?</em>

					<!-- Input booking -->
					<form action="form_action.asp" method="get">
					  start time: <input type="text" name="stime" /><br />
					  end time: <input type="text" name="etime" /><br />
					  <input type="submit" value="Submit" />
					</form>
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