# Chartee - a Magento 2 chart-js module 

This module should ease the way of creating powerful charts in Magento 2. It is based on the chart.js library (which itself is already used by Magento2 for its dashboard charts) and various extensions for additional chart types.

**Goals of this module:** 

* Be independent of the version management of Magento and updates of its library dependencies (only the current 2.4.7-beta2 uses chart.js v4.4.0!) and use newer chart.js versions in older Magento2 installations
* Provide a simple way to create charts in Magento2 without much knowledge of the chart.js library itself
* Provide boilerplate codes to easily integrate any type of chart in various places within Magento
* Encourage everyone to create meaningful representations of data already available in Magento2, no stackholder would see otherwise as it is buried in the databases

## Installation

```
composer require brocode/module-chartee
bin/magento module:enable BroCode_Chartee
bin/magento setup:upgrade
```

## Features

### Supported chart types

**Bar chart**
Based on the base implementation from https://www.chartjs3.com/docs/chart/getting-started/

![Bar chart](/docs/demo_barchart.png)

**Stacked bar chart**

A bar chart variation with the help of the chart.js plugin https://github.com/y-takey/chartjs-plugin-stacked100/
![Stacked bar chart](/docs/demo_stackedbarchart.png)

**Polar chart**

A polar chart based on the sample from https://codepen.io/FranciscusAgnew/pen/LRGPYX
![Polar chart](/docs/demo_polarchart.png)

**Doughnut chart**

A variation of the base doughnut chart, based on https://www.youtube.com/watch?v=YcRj52VovYQ
![Doughnut chart](/docs/demo_doughnutchart.png)

**Gauge chart**

A variation of the doughnut chart, based on https://www.youtube.com/watch?v=VF6jd2Fv4bs&list=RDCMUCojXvfr41NqDxaPb9amu8-A&index=1
![Gauge chart](/docs/demo_gaugechart.png)

**Boxplot chart**

*What is a Boxplot chart? See [Wikipedia](https://en.wikipedia.org/wiki/Box_plot) for more information.*

A boxplot chart, based on https://www.youtube.com/watch?v=2zEbeX0bPS8
![Boxplot chart](/docs/demo_boxplotchart.png)

### Ready to use charts

**Customer Group Distribution**

The charts shows the distribution of registered customers between all customer groups. It also provides the possibility to download the shown data as CSV. 

It can be found in the page  ```Customers -> Customer Groups```:
![Customer Group Distribution](/docs/customergroup_distribution_chart.png)

*Permissions:* only admin users with the permission ```BroCode_Chartee::chart_customer_group_distribution``` can see the chart.
![Customer Group Distribution Permissions](/docs/acl_configuration.png)

*Configuration:*  ```Stores -> Configuration -> Services -> Chartee Reports Configuration -> Customer Report Settings```
* the chart can be disabled via configuration 
* the download filename can be changed via configuration
![Customer Group Distribution Configuration](/docs/customer_chart_configuration.png)

### Adminhtml menu items

All menus can be found under ```Content -> BroCode Chartee```
![Adminhtml menu items](/docs/menu_items.png)

**Chartbuilder Listing**

Shows all registered chart builders.
![Chartbuilder Listing](/docs/chartbuilder_listing.png)

Currently no further features are implemented. Possible features could be:
* Preview of generated chart.js configurations (TBD)

**Demo Charts**

Listing of all current demo charts and links how they have been implemented on js side.

### Boilerplate code


TODO samples on how to use it


```xml
<block class="BroCode\Chartee\Block\Widget\BaseChart" name="chartee.boxplot" before="-">
    <action method="setViewModel">
        <argument name="viewModel" xsi:type="object">
            BroCode\Chartee\Block\ViewModel\DemoBoxplotChartConfiguration
        </argument>
    </action>
</block>
```