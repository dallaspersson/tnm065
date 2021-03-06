<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:exslt="http://exslt.org/common" xmlns:date="http://exslt.org/dates-and-times" 
    extension-element-prefixes="ex">
	<xsl:template match="*">
		<div class="body_content">
			<div class="resources">
				<h2>Resources</h2>
				<xsl:apply-templates select="resources" />
				<a href="?resource&#38;add">Add resource</a>
			</div>
			<div class="calander-content">

				<div class="calander">

					<!-- Current month and year 
					<h1 class="big-date">
						<xsl:value-of select="date:month-name()"/>
						<xsl:text> </xsl:text>
						<xsl:value-of select="date:year()"/>
					</h1>-->

					<!-- Current status
					<em>Current status?</em> -->

					<xsl:call-template name="Calendar" />
					
				</div>

				<div class="bookings">
					<h2>Bookings</h2>
					<!-- List bookings -->
					<xsl:apply-templates select="bookings" />
					<a href="?booking&#38;add">New booking</a>
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
					  		?resource_id=<xsl:value-of select="id"/>
					  	</xsl:attribute>
		      			<p class="current-resource"><xsl:value-of select="resource-type"/></p>
	      			</xsl:element>
		      	</xsl:when>
		      	<xsl:otherwise>
		      		<xsl:element name="a">
					  	<xsl:attribute name="href">
					  		<!-- Link to correct schedual-->
					  		?resource_id=<xsl:value-of select="id"/>
					  	</xsl:attribute>
		      			<p class="not-current-resource"><xsl:value-of select="resource-type"/></p>
	      			</xsl:element>
		      	</xsl:otherwise>
	      	</xsl:choose>
	      	<a href="?resource&#38;remove&#38;id={id}">Remove</a>
	    </xsl:for-each>
  	</xsl:template>

  	<xsl:template match="resource">
		<xsl:apply-templates />
  	</xsl:template>

  	<xsl:template match="bookings">
  		<!-- Loop through all bookings -->
		<xsl:for-each select="booking">

			<xsl:variable name="current_user_id">
				<xsl:value-of select="user-id" />
			</xsl:variable>

				<div class="fat-bottom">
					<div class="tight">
						<xsl:apply-templates select="booked-slots"/><br />

						<!-- Print user --> 
						<em class="comment-text">
							<xsl:for-each select="/timeslot/users/user">
								<xsl:choose>
									<xsl:when test="id = $current_user_id">
										<xsl:value-of select="firstname" />
										<xsl:text> </xsl:text>
										<xsl:value-of select="lastname" />
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="$current_user_id"/> 
										<xsl:value-of select="id" />
									</xsl:otherwise>
								</xsl:choose>
								
							</xsl:for-each>
						</em>

						
					</div>


					<div class="booking-button-right">
						<!-- Remove button -->
						<a href="?booking&#38;remove&#38;booking_id={id}">Remove</a>
					</div>

				</div>
			
		</xsl:for-each>
  	</xsl:template>

  	<xsl:template match="booked-slots">
		<xsl:for-each select="slot-id">
			<xsl:text>Slot </xsl:text>
			<xsl:apply-templates />
		</xsl:for-each>
  	</xsl:template>



  	<!-- Code for the calander (call with <xsl:call-template name="Calendar" />) -->
  	<xsl:variable name="DisplayDate" select="date:date()"/> 
  	<xsl:variable name="Today" select="date:day-in-month()"/> 
	<xsl:variable name="Year" select="date:year($DisplayDate)"/> 
	<xsl:variable name="Month" select="date:month-in-year($DisplayDate)"/> 
	<xsl:variable name="MonthName" select="date:month-name($DisplayDate)" /> 
	<xsl:variable name="NumberOfDaysInMonth"> 
	  <xsl:call-template name="DaysInMonth"> 
	    <xsl:with-param name="month" select="$Month" /> 
	    <xsl:with-param name="year" select="$Year" /> 
	  </xsl:call-template> 
	</xsl:variable> 
	<xsl:variable name="FirstDayInWeekForMonth">
	  <xsl:choose>
	    <xsl:when test="$Month &lt; 10">
	      <xsl:value-of select="date:day-in-week(date:date(concat($Year,'-0', $Month, '-01')))" />
	    </xsl:when>
	    <xsl:otherwise>
	      <xsl:value-of select="date:day-in-week(date:date(concat($Year,'-', $Month, '-01')))" />
	    </xsl:otherwise>
	  </xsl:choose>
	</xsl:variable> 
	<xsl:variable name="WeeksInMonth"><xsl:value-of select="($NumberOfDaysInMonth + $FirstDayInWeekForMonth - 1) div 7" /></xsl:variable> 

	<xsl:template name="DaysInMonth"> 
	  <xsl:param name="month"><xsl:value-of select="$Month" /></xsl:param> 
	  <xsl:param name="year"><xsl:value-of select="$Year" /></xsl:param> 
	  <xsl:choose> 
	    <xsl:when test="$month = 1 or $month = 3 or $month = 5 or $month = 7 or $month = 8 or $month = 10 or $month = 12">31</xsl:when> 
	    <xsl:when test="$month=2"> 
	      <xsl:choose> 
	        <xsl:when test="$year mod 4 = 0">29</xsl:when> 
	        <xsl:otherwise>28</xsl:otherwise> 
	      </xsl:choose> 
	    </xsl:when> 
	    <xsl:otherwise>30</xsl:otherwise> 
	  </xsl:choose> 
	</xsl:template> 

	<xsl:template name="Calendar"> 
	  <table class="calendar-table" summary="Monthly calendar"> 
	    <caption> 
    		<h1 class="big-date">
				<xsl:value-of select="$MonthName" /> 
				<xsl:text> </xsl:text> 
				<xsl:value-of select="$Year" /> 
	  		</h1>
	    </caption> 
	    <tr> 
	      <th abbr="Sunday">Sun</th> 
	      <th abbr="Monday">Mon</th> 
	      <th abbr="Tuesday">Tue</th> 
	      <th abbr="Wednesday">Wed</th> 
	      <th abbr="Thursday">Thu</th> 
	      <th abbr="Friday">Fri</th> 
	      <th abbr="Saturday">Sat</th> 
	    </tr> 
	    <xsl:call-template name="CalendarWeek"/> 
	  </table> 
	</xsl:template> 

	<xsl:template name="CalendarWeek"> 
	  <xsl:param name="week">1</xsl:param> 
	  <xsl:param name="day">1</xsl:param> 
	  <tr> 
	    <xsl:call-template name="CalendarDay"> 
	      <xsl:with-param name="day" select="$day" /> 
	    </xsl:call-template> 
	  </tr> 
	  <xsl:if test="$WeeksInMonth &gt; $week"> 
	    <xsl:call-template name="CalendarWeek"> 
	      <xsl:with-param name="week" select="$week + 1" /> 
	      <xsl:with-param name="day" select="$week * 7 - ($FirstDayInWeekForMonth - 2)" /> 
	    </xsl:call-template> 
	  </xsl:if> 
	</xsl:template> 

	<xsl:template name="CalendarDay"> 
	  <xsl:param name="count">1</xsl:param> 
	  <xsl:param name="day" /> 
	  <xsl:choose> 
	    <xsl:when test="($day = 1 and $count != $FirstDayInWeekForMonth) or $day &gt; $NumberOfDaysInMonth"> 
	      <td class="empty"><xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text></td> 
	      <xsl:if test="$count &lt; 7"> 
	        <xsl:call-template name="CalendarDay"> 
	          <xsl:with-param name="count" select="$count + 1" /> 
	          <xsl:with-param name="day" select="$day" /> 
	        </xsl:call-template> 
	      </xsl:if> 
	    </xsl:when> 
	    <xsl:otherwise> 
				<td> 
					<xsl:element name="a">
					  	<xsl:attribute name="href">#
					  	</xsl:attribute>
					  	<div class="cell-link">
							<xsl:choose>
								<!-- If today -->
								<xsl:when test="$day = $Today">
									<div class="today">
										<p>Today</p>
									</div>
									<strong>
										<xsl:value-of select="$day" /> 
									</strong>
								</xsl:when>
								<xsl:otherwise>
								<xsl:value-of select="$day" /> 
							</xsl:otherwise>
							</xsl:choose>
						</div>
					</xsl:element>
				</td> 
	      <xsl:if test="$count &lt; 7"> 
	        <xsl:call-template name="CalendarDay"> 
	          <xsl:with-param name="count" select="$count + 1" /> 
	          <xsl:with-param name="day" select="$day + 1" /> 
	        </xsl:call-template> 
	      </xsl:if> 
	    </xsl:otherwise> 
	  </xsl:choose> 
	</xsl:template>

  	
</xsl:stylesheet>