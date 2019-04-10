<?php
// $Id help/stats_monthly_sales.php 1.0a
//

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Monthly Sales/Tax Report</title>
<link rel="stylesheet" type="text/css" href="../includes/stylesheet.css">
</head>
<BODY>
<center>
<table width="95%"><tr><td>
<p class="main" align="center">
<b>How to view and use the store income summary report</b>
<p class="main" align="justify">
<b>Selected from the Reports menu</b>
<p class="smallText" align="justify">
When initially selected from the Reports menu, this report displays a financial summary of all orders in the store database.  Each month of the store's history is summarized in a row, showing all store income and its components, divided into taxable and exempt sales, and listing the amounts of taxes, shipping and handling charges, low order fees and gift vouchers. (If the store does not have low order fees or gift vouchers enabled, these columns are omitted from the report.)
<p class="smallText" align="justify">
The top row is the current month, and the rows under it summarize each month of the store's order history.  Beneath the rows of each calendar year is a footer line, summarizing that year's totals in each column of the report.
<p class="main" align="justify">
<b>What the columns represent (headers explained)</b>
<p class="smallText" align="justify">
On the left, the month and year of the row are stated.  The other columns are, left to right:
<ul><li class="smallText"><b>Gross Income</b> - the sum total of all sales, taxes and other charges accumulated from the orders made in that month
<li class="smallText"><b>Product sales</b> - the total sales of products purchased in the month
<br>Then, the product sales are broken into two categories:
<li class="smallText"><b>Exempt sales</b> - product sales which were shipped outside the store's zone (exempt from sales tax), and
<li class="smallText"><b>Taxable sales</b> - product sales which were shipped within the store's zone (subject to sales tax)
<li class="smallText"><b>Taxes paid</b> - the amount charged to customers and included in their order amount for taxes
<li class="smallText"><b>Shipping & handling</b> - the total shipping and handling charges for the orders
<li class="smallText"><b>Low order fees</b> and <b>Gift Vouchers</b> - if the store has low order fees enabled, and/or gift vouchers, the totals of these are shown in separate columns
</ul>
<p class="main" align="justify">
<b>Selecting report summary by status</b>
<p class="smallText" align="justify">
To show the monthly summary information for just one Order Status, select the status in the drop-down box at the upper right of the report screen.  Depending on the store's setup for these values, there may be a status for "Pending" or "Shipped" for instance.  Change this status and the report will be recalculated and displayed.
<p class="main" align="justify">
<b>Printing the report</b>
<p class="smallText" align="justify">
To view the report in a printer-friendly window, click on the Print button next to the File and Help buttons, then user your browser's print command in the File menu.  The store name and headers are added to show what orders were selected, and when the report was generated.
<p class="main" align="justify">
<b>Saving report values to a file</b>
<p class="smallText" align="justify">
<i>If 'File' does not appear between 'Print' and 'Help' on your system, this feature has been disabled by your system administrator, whom you should consult for further information.</i>
<p class="smallText" align="justify">
To save the values of the report to a file, click on the File button between the Print and Help buttons.  The report values will be sent to your browser in a text file, and you will be prompted with a Save File dialog box to choose where to save the file.  The contents of the file are in Comma Separated Value (CSV) format, with a line for each row of the report beginning with the header line, and each value in the row is separated by commas. This file can be conveniently and accurately imported to common spreadsheet financial and statistical tools, such as Excel and QuattroPro. The file is provided to your browser with a suggested file name consisting of the report name, status selected, and date/time. <br><br>
</td></tr>
</table>
</BODY>
</HTML>
