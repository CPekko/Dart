$( document ).ready(function() {
    $(function () {
        var seriesOptions = [],
            seriesCounter = 0,
            names = ['Accuracy Histogram'],
            // create the chart when all data is loaded
            createAccChart = function () {
                $('#accuracyChart').highcharts({
                    title: true,
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        formatter: function () {
                            if (this.x === 0) return $('#stats').data('user-name') + ' has hit target <b>' + this.y +
                                '</b> times';
                            return $('#stats').data('user-name') + ' missed <b>' + this.y +
                                '</b> throws by ' + this.x;
                        }
                    },
                    xAxis: {
                        title: {
                            text: 'Distance from Target'
                        },
                        currentMin: 0,
                        currentMax: 10
                    },
                    yAxis: {
                        title: {
                            text: 'Number of Throws'
                        }
                    },
                    loading: true,
                    series: seriesOptions
                });
            };

        $.each(names, function (i, name) {

            $.getJSON('http://folk.ntnu.no/eiriknf/dart/stats/getStats.php?id=' + $('#stats').data('user-id') + '&type=1&callback=?', function (data) {

                seriesOptions[i] = {
                    name: $('#stats').data('user-name'),
                    type: 'column',
                    data: data
                };

                // As we're loading the data asynchronously, we don't know what order it will arrive. So
                // we keep a counter and create the chart when all the data is loaded.
                seriesCounter += 1;

                if (seriesCounter === names.length) {
                    createAccChart();
                }
            });
        });
    });

    $(function () {
        var seriesOptions = [],
            seriesCounter = 0,
            names = ['Streak Length Histogram'],
            // create the chart when all data is loaded
            createStreakChart = function () {
                $('#streakChart').highcharts({
                    title: true,
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        formatter: function () {
                            return $('#stats').data('user-name') + ' has <b>' + this.y +
                                '</b> streaks of ' + this.x;
                        }
                    },
                    xAxis: {
                        title: {
                            text: 'Streak Length'
                        },
                        currentMin: 0,
                        currentMax: 10
                    },
                    yAxis: {
                        title: {
                            text: 'Number of Times Achieved'
                        }
                    },
                    loading: true,
                    series: seriesOptions
                });
            };

        $.each(names, function (i, name) {

            $.getJSON('http://folk.ntnu.no/eiriknf/dart/stats/getStats.php?id=' + $('#stats').data('user-id') + '&type=2&callback=?', function (data) {

                seriesOptions[i] = {
                    name: $('#stats').data('user-name'),
                    type: 'column',
                    data: data
                };

                // As we're loading the data asynchronously, we don't know what order it will arrive. So
                // we keep a counter and create the chart when all the data is loaded.
                seriesCounter += 1;

                if (seriesCounter === names.length) {
                    createStreakChart();
                }
            });
        });
    });

    $(function () {
        var seriesOptions = [],
            seriesCounter = 0,
            names = ['Per Target Hit Ratio'],
            // create the chart when all data is loaded
            createHitChart = function () {
                $('#hitRatioChart').highcharts({
                    title: true,
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        formatter: function () {
                            return 'Hit ratio for ' + this.x +
                                ': <b>' + this.y + '%</b>';
                        }
                    },
                    xAxis: {
                        title: {
                            text: 'Target'
                        },
                        categories: ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20'],
                        labels:{
                            step: 1
                        },
                        currentMin: 1,
                        currentMax: 20
                    },
                    yAxis: {
                        title: {
                            text: 'Hit Ratio (%)'
                        },
                        labels: {
                            format: "{value}%"
                        },
                        max: 100
                    },
                    loading: true,
                    series: seriesOptions
                });
            };

        $.each(names, function (i, name) {

            $.getJSON('http://folk.ntnu.no/eiriknf/dart/stats/getStats.php?id=' + $('#stats').data('user-id') + '&type=3&callback=?', function (data) {

                seriesOptions[i] = {
                    name: $('#stats').data('user-name'),
                    type: 'column',
                    data: data
                };

                // As we're loading the data asynchronously, we don't know what order it will arrive. So
                // we keep a counter and create the chart when all the data is loaded.
                seriesCounter += 1;

                if (seriesCounter === names.length) {
                    createHitChart();
                }
            });
        });
    });
    $(function () {
        var seriesOptions = [],
            seriesCounter = 0,
            names = ['Total Hits per Field'],
            // create the chart when all data is loaded
            createHitTarget = function () {
                $('#hitTargetChart').highcharts({
                    title: true,
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        formatter: function () {
                            return 'Total hits on ' + this.x +
                                ': <b>' + this.y + '</b>';
                        }
                    },
                    xAxis: {
                        title: {
                            text: 'Target'
                        },
                        categories: ['Miss','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','25','50'],
                        labels:{
                            step: 1
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Hits'
                        }
                    },
                    loading: true,
                    series: seriesOptions
                });
            };

        $.each(names, function (i, name) {

            $.getJSON('http://folk.ntnu.no/eiriknf/dart/stats/getStats.php?id=' + $('#stats').data('user-id') + '&type=4&callback=?', function (data) {

                seriesOptions[i] = {
                    name: $('#stats').data('user-name'),
                    type: 'column',
                    data: data
                };

                // As we're loading the data asynchronously, we don't know what order it will arrive. So
                // we keep a counter and create the chart when all the data is loaded.
                seriesCounter += 1;

                if (seriesCounter === names.length) {
                    createHitTarget();
                }
            });
        });
    });
});