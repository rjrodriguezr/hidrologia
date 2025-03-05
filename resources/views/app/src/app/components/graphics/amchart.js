//Se copiaron los archivos de la libreria amchart y no se instalaron por bower ni npm
angular.module("chart", [])
  .directive("chartSheque", function () {
    return {
      restrict: "E",
      scope: {
        data: '='
      },
      template: "<div id='chartdiv'></div>",
      replace: true,
      link: function ($scope) {
        $scope.$watch("data", function (newValue, oldValue) {
          var chart = am4core.create("chartdiv", am4charts.XYChart);
          am4core.options.commercialLicense = true;

          chart.paddingRight = 20;

          chart.data = $scope.data;

          const categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
          categoryAxis.dataFields.category = 'hora';
          categoryAxis.title.text = 'Horas';
          categoryAxis.renderer.grid.template.location = 0;
          categoryAxis.renderer.minGridDistance = 10;
          categoryAxis.renderer.grid.template.disabled = true;
          categoryAxis.renderer.fullWidthTooltip = true;

          const valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
          valueAxis.title.text = 'Caudal Total (m3/s)';
          valueAxis.renderer.opposite = false;

          const series0 = chart.series.push(new am4charts.LineSeries());
          series0.name = 'Canchis';
          series0.dataFields.valueY = 'hisd_q_canchis';
          series0.dataFields.categoryX = 'hora';
          series0.tooltipText = 'Canchis: {valueY} m3/s';
          series0.strokeWidth = 1;
          series0.stroke = am4core.color('#797979');
          series0.tooltip.getFillFromObject = false;
          series0.tooltip.background.fill = am4core.color('#fff');
          series0.tooltip.label.fill = am4core.color('#558b2f');
          series0.propertyFields.strokeDasharray = 'dashLength';
          series0.showOnInit = true;
          const serie0Bullet = series0.bullets.push(new am4charts.CircleBullet());
          serie0Bullet.circle.fill = am4core.color('#797979');
          serie0Bullet.circle.strokeWidth = 1;
          serie0Bullet.circle.radius = 3;
          serie0Bullet.circle.propertyFields.radius = 'townSize';
          const serie0State = serie0Bullet.states.create('hover');
          serie0State.properties.scale = 1.2;

          const series1 = chart.series.push(new am4charts.LineSeries());
          series1.name = 'Sheque';
          series1.dataFields.valueY = 'hisd_q_total_cal';
          series1.dataFields.categoryX = 'hora';
          series1.tooltipText = 'Sheque: {valueY} m3/s';
          series1.strokeWidth = 1;
          series1.stroke = am4core.color('#184EBB');
          series1.tooltip.getFillFromObject = false;
          series1.tooltip.background.fill = am4core.color('#fff');
          series1.tooltip.label.fill = am4core.color('#558b2f');
          series1.propertyFields.strokeDasharray = 'dashLength';
          series1.showOnInit = true;
          const serie1Bullet = series1.bullets.push(new am4charts.CircleBullet());
          serie1Bullet.circle.fill = am4core.color('#184EBB');
          serie1Bullet.circle.strokeWidth = 1;
          serie1Bullet.circle.radius = 3;
          serie1Bullet.circle.propertyFields.radius = 'townSize';
          const serie1State = serie1Bullet.states.create('hover');
          serie1State.properties.scale = 1.2;

          const series2 = chart.series.push(new am4charts.LineSeries());
          series2.name = 'Tamboraque';
          series2.dataFields.valueY = 'hitd_q_total_disp_h';
          series2.dataFields.categoryX = 'hora';
          series2.tooltipText = 'Tamboraque: {valueY} m3/s';
          series2.strokeWidth = 1;
          series2.stroke = am4core.color('#FF9000');
          series2.tooltip.getFillFromObject = false;
          series2.tooltip.background.fill = am4core.color('#fff');
          series2.tooltip.label.fill = am4core.color('#558b2f');
          series2.propertyFields.strokeDasharray = 'dashLength';
          series2.showOnInit = true;
          const serie2Bullet = series2.bullets.push(new am4charts.CircleBullet());
          serie2Bullet.circle.fill = am4core.color('#FF9000');
          serie2Bullet.circle.strokeWidth = 1;
          serie2Bullet.circle.radius = 3;
          serie2Bullet.circle.propertyFields.radius = 'townSize';
          const serie2State = serie2Bullet.states.create('hover');
          serie2State.properties.scale = 1.2;

          const series3 = chart.series.push(new am4charts.LineSeries());
          series3.name = 'Huampani';
          series3.dataFields.valueY = 'hitd_q_total_disp_t';
          series3.dataFields.categoryX = 'hora';
          series3.tooltipText = 'Huampani: {valueY} m3/s';
          series3.strokeWidth = 1;
          series3.stroke = am4core.color('#A7292C');
          series3.tooltip.getFillFromObject = false;
          series3.tooltip.background.fill = am4core.color('#fff');
          series3.tooltip.label.fill = am4core.color('#558b2f');
          series3.propertyFields.strokeDasharray = 'dashLength';
          series3.showOnInit = true;
          const serie3Bullet = series3.bullets.push(new am4charts.CircleBullet());
          serie3Bullet.circle.fill = am4core.color('#A7292C');
          serie3Bullet.circle.strokeWidth = 1;
          serie3Bullet.circle.radius = 3;
          serie3Bullet.circle.propertyFields.radius = 'townSize';
          const serie3State = serie3Bullet.states.create('hover');
          serie3State.properties.scale = 1.2;

          const series4 = chart.series.push(new am4charts.LineSeries());
          series4.name = 'Moyopampa';
          series4.dataFields.valueY = 'hitd_q_total_disp_m';
          series4.dataFields.categoryX = 'hora';
          series4.tooltipText = 'Moyopampa: {valueY} m3/s';
          series4.strokeWidth = 1;
          series4.stroke = am4core.color('#228b22');
          series4.tooltip.getFillFromObject = false;
          series4.tooltip.background.fill = am4core.color('#fff');
          series4.tooltip.label.fill = am4core.color('#558b2f');
          series4.propertyFields.strokeDasharray = 'dashLength';
          series4.showOnInit = true;
          const serie4Bullet = series4.bullets.push(new am4charts.CircleBullet());
          serie4Bullet.circle.fill = am4core.color('#228b22');
          serie4Bullet.circle.strokeWidth = 1;
          serie4Bullet.circle.radius = 3;
          serie4Bullet.circle.propertyFields.radius = 'townSize';
          const serie4State = serie4Bullet.states.create('hover');
          serie4State.properties.scale = 1.2;

          chart.legend = new am4charts.Legend();
          chart.cursor = new am4charts.XYCursor();
          chart.cursor.fullWidthLineX = true;
          chart.cursor.xAxis = categoryAxis;
          chart.cursor.lineX.strokeOpacity = 0;
          chart.cursor.lineX.fill = am4core.color('#000');
          chart.cursor.lineX.fillOpacity = 0.1;

          $scope.$on("$destroy", function () {
            chart.dispose();
          });
        });
        
      }
    };
  })
  .directive("chartChinango", function () {
    return {
      restrict: "E",
      scope: {
        data: '='
      },
      template: "<div id='chartdiv'></div>",
      replace: true,
      link: function ($scope) {
        $scope.$watch("data", function (newValue, oldValue) {
          var chart = am4core.create("chartdiv", am4charts.XYChart);
          am4core.options.commercialLicense = true;

          chart.paddingRight = 20;

          chart.data = $scope.data;

          const categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
          categoryAxis.dataFields.category = 'hora';
          categoryAxis.title.text = 'Horas';
          categoryAxis.renderer.grid.template.location = 0;
          categoryAxis.renderer.minGridDistance = 10;
          categoryAxis.renderer.grid.template.disabled = true;
          categoryAxis.renderer.fullWidthTooltip = true;

          const valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
          valueAxis.title.text = 'Caudal (m3/s)';
          valueAxis.renderer.opposite = false;

          const series0 = chart.series.push(new am4charts.LineSeries());
          series0.name = 'Tulumayo';
          series0.dataFields.valueY = 'hiyd_q_total_disp';
          series0.dataFields.categoryX = 'hora';
          series0.tooltipText = 'Tulumayo: {valueY} m3/s';
          series0.strokeWidth = 1;
          series0.stroke = am4core.color('#228b22');
          series0.tooltip.getFillFromObject = false;
          series0.tooltip.background.fill = am4core.color('#fff');
          series0.tooltip.label.fill = am4core.color('#558b2f');
          series0.propertyFields.strokeDasharray = 'dashLength';
          series0.showOnInit = true;
          const serie0Bullet = series0.bullets.push(new am4charts.CircleBullet());
          serie0Bullet.circle.fill = am4core.color('#228b22');
          serie0Bullet.circle.strokeWidth = 1;
          serie0Bullet.circle.radius = 3;
          serie0Bullet.circle.propertyFields.radius = 'townSize';
          const serie0State = serie0Bullet.states.create('hover');
          serie0State.properties.scale = 1.2;

          const series1 = chart.series.push(new am4charts.LineSeries());
          series1.name = 'Cap.Tul';
          series1.dataFields.valueY = 'hiyd_q_captado';
          series1.dataFields.categoryX = 'hora';
          series1.tooltipText = 'Cap.Tul: {valueY} m3/s';
          series1.strokeWidth = 1;
          series1.stroke = am4core.color('#184EBB');
          series1.tooltip.getFillFromObject = false;
          series1.tooltip.background.fill = am4core.color('#fff');
          series1.tooltip.label.fill = am4core.color('#558b2f');
          series1.propertyFields.strokeDasharray = 'dashLength';
          series1.showOnInit = true;
          const serie1Bullet = series1.bullets.push(new am4charts.CircleBullet());
          serie1Bullet.circle.fill = am4core.color('#184EBB');
          serie1Bullet.circle.strokeWidth = 1;
          serie1Bullet.circle.radius = 3;
          serie1Bullet.circle.propertyFields.radius = 'townSize';
          const serie1State = serie1Bullet.states.create('hover');
          serie1State.properties.scale = 1.2;

          const series2 = chart.series.push(new am4charts.LineSeries());
          series2.name = 'Tarma';
          series2.dataFields.valueY = 'hitd_q_total_disp_t';
          series2.dataFields.categoryX = 'hora';
          series2.tooltipText = 'Tarma: {valueY} m3/s';
          series2.strokeWidth = 1;
          series2.stroke = am4core.color('#FF9000');
          series2.tooltip.getFillFromObject = false;
          series2.tooltip.background.fill = am4core.color('#fff');
          series2.tooltip.label.fill = am4core.color('#558b2f');
          series2.propertyFields.strokeDasharray = 'dashLength';
          series2.showOnInit = true;
          const serie2Bullet = series2.bullets.push(new am4charts.CircleBullet());
          serie2Bullet.circle.fill = am4core.color('#FF9000');
          serie2Bullet.circle.strokeWidth = 1;
          serie2Bullet.circle.radius = 3;
          serie2Bullet.circle.propertyFields.radius = 'townSize';
          const serie2State = serie2Bullet.states.create('hover');
          serie2State.properties.scale = 1.2;

          const series3 = chart.series.push(new am4charts.LineSeries());
          series3.name = 'Cap.Tarma';
          series3.dataFields.valueY = 'hitd_q_captado_t';
          series3.dataFields.categoryX = 'hora';
          series3.tooltipText = 'Cap.Tarma: {valueY} m3/s';
          series3.strokeWidth = 1;
          series3.stroke = am4core.color('#A7292C');
          series3.tooltip.getFillFromObject = false;
          series3.tooltip.background.fill = am4core.color('#fff');
          series3.tooltip.label.fill = am4core.color('#558b2f');
          series3.propertyFields.strokeDasharray = 'dashLength';
          series3.showOnInit = true;
          const serie3Bullet = series3.bullets.push(new am4charts.CircleBullet());
          serie3Bullet.circle.fill = am4core.color('#fff');
          serie3Bullet.circle.strokeWidth = 1;
          serie3Bullet.circle.radius = 3;
          serie3Bullet.circle.propertyFields.radius = 'townSize';
          const serie3State = serie3Bullet.states.create('hover');
          serie3State.properties.scale = 1.2;

          chart.legend = new am4charts.Legend();
          chart.cursor = new am4charts.XYCursor();
          chart.cursor.fullWidthLineX = true;
          chart.cursor.xAxis = categoryAxis;
          chart.cursor.lineX.strokeOpacity = 0;
          chart.cursor.lineX.fill = am4core.color('#000');
          chart.cursor.lineX.fillOpacity = 0.1;

          $scope.$on("$destroy", function () {
            chart.dispose();
          });
        });        
        
      }
    };
  });