<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="*">
		<div class="body_content">
			<div class="resources">
				<h2>Resources</h2>
				<xsl:apply-templates select="resources" />
			</div>
			<div class="calander-content">

				<div class="calander">

					<!-- Current month and year -->
					<h1 class="big-date">December 2011</h1>

					<!-- Current status -->
					<em>Current status?</em>

					<!-- Input booking -->
					<form method="post">
		 				<input type="hidden" value="booking"/>
		 			
		 				<label for="booking-start-time">Start Time</label>
						<input type="datetime" id="booking-start-time" name="booking-start-time"/><br />
						
						<label for="booking-end-time">End Time</label>
						<input type="datetime" id="booking-end-time" name="booking-end-time"/><br />
						
						<input type="submit" value="Book"/>
					</form>

					
				</div>

				<div class="bookings">
					<h2>Bookings</h2>
					<!-- List bookings -->
					<xsl:apply-templates select="bookings" />
				</div>

			</div>
		</div>
	</xsl:template>



	<!-- Template match -->

	<xsl:template match="resources">
		<xsl:for-each select="resource">
			<xsl:choose>
				<xsl:when test="position() = 1">
					<xsl:element name="a">
					  	<xsl:attribute name="href">
					  		<!-- Link to correct schedual-->
					  		<!--<xsl:value-of select="link" />-->
					  	</xsl:attribute>
		      			<p class="current-resource"><xsl:value-of select="resource-type"/></p>
	      			</xsl:element>
		      	</xsl:when>
		      	<xsl:otherwise>
		      		<xsl:element name="a">
					  	<xsl:attribute name="href">
					  		<!-- Link to correct schedual-->
					  		<!--<xsl:value-of select="link" />-->
					  	</xsl:attribute>
		      			<p class="not-current-resource"><xsl:value-of select="resource-type"/></p>
	      			</xsl:element>
		      	</xsl:otherwise>
	      	</xsl:choose>
	    </xsl:for-each>
  	</xsl:template>

  	<xsl:template match="resource">
		<xsl:apply-templates />
  	</xsl:template>

  	<xsl:template match="bookings">
  		<!-- Loop through all bookings -->
		<xsl:for-each select="booking">
			<div class="fat-bottom">
				<div class="tight">
					<xsl:apply-templates select="booked-slots"/><br />

					<!-- Print user --> 
					<em><xsl:value-of select="user-id" /></em>

					
				</div>

				<div class="booking-button-right">
					<!-- Remove button -->
					<input TYPE="button" onClick="#" value="Remove"/>
				</div>

			</div>
		</xsl:for-each>
  	</xsl:template>

  	<xsl:template match="booked-slots">
		<xsl:for-each select="slot-id">
			<xsl:apply-templates />
		</xsl:for-each>
  	</xsl:template>

  	
</xsl:stylesheet>