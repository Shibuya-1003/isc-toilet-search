document.addEventListener('DOMContentLoaded', function () {
    let obniz = new Obniz("YOUR_OBNIZ_ID");
//    let distSensor1;
    let distSensor2;

    let sensorNo1 = 1;
    let sensorNo2 = 2;

    let previousStatus1 = null;
    let previousStatus2 = null;

    let time1 = null;
    let time2 = null;

    obniz.onconnect = async function () {
//        distSensor1 = obniz.wired("GP2Y0A21YK0F", { vcc: 0, gnd: 1, signal: 2 }); //センサー1
        distSensor2 = obniz.wired("GP2Y0A21YK0F", { vcc: 6, gnd: 7, signal: 8 }); //センサー2

        //磁気センサー
        obniz.io0.pull("5v");
        obniz.io1.output(false);
        obniz.io0.input(function(value){
            // 状態を判定
            let currentStatus1 = !value ? "使用中" : "未使用";

            // 状態が変わったときだけ処理
            if (currentStatus1 !== previousStatus1) {
                let elapsedTime1 = time1 ? Math.floor((Date.now() - time1) / 1000) : 0; // 経過時間を計算

                //表示更新
                $('#print').text(currentStatus1);
                obniz.display.clear();
                obniz.display.print(currentStatus1);

                if (currentStatus1 === "使用中") {
                    time1 = Date.now(); // 使用開始時間を記録
                }

                console.log("送信準備：", {
                    No: sensorNo1,
                    Time: elapsedTime1,
                    Status: currentStatus1,
                    Distance: value
                });
                // 送信処理
                $.post("save_data.php", {
                    No: sensorNo1,
                    Time: elapsedTime1,
                    Status: currentStatus1,
                    Distance: value
                }, function (data) {
                    console.log("送信完了", data);
                });

                previousStatus1 = currentStatus1; // 現在の状態を保存
            }
        });

        setInterval(async function () { // センサー1の処理
/*
            // 距離センサー値の平均をとる
            let distanceSum1 = 0;
            for (let i = 0; i < 5; i++) {
                distanceSum1 += await distSensor1.getWait();
                await new Promise(resolve => setTimeout(resolve, 100));
            }
            let distance1 = distanceSum1 / 5;

            // 状態を判定
            let currentStatus1 = (distance1 > 300) ? "使用中" : "未使用";

            // 状態が変わったときだけ処理
            if (currentStatus1 !== previousStatus1 && !isNaN(distance1) && distance1 > 0) {
                let elapsedTime1 = time1 ? Math.floor((Date.now() - time1) / 1000) : 0; // 経過時間を計算

                //表示更新
                $('#print').text(currentStatus1);
                obniz.display.clear();
                obniz.display.print(currentStatus1);


                if (currentStatus1 === "使用中") {
                    time1 = Date.now(); // 使用開始時間を記録
                }

                console.log("送信準備：", {
                    No: sensorNo1,
                    Time: elapsedTime1,
                    Status: currentStatus1,
                    Distance: distance1
                });
                // 送信処理
                $.post("save_data.php", {
                    No: sensorNo1,
                    Time: elapsedTime1,
                    Status: currentStatus1,
                    Distance: distance1
                }, function (data) {
                    console.log("送信完了", data);
                });

                previousStatus1 = currentStatus1; // 現在の状態を保存
            }
*/
            let distance2Sum = 0;
            for (let i = 0; i < 5; i++) {
                distance2Sum += await distSensor2.getWait();
                await new Promise(resolve => setTimeout(resolve, 100));
            }
            let distance2 = distance2Sum / 5;
            let currentStatus2 = (distance2 > 300) ? "使用中" : "未使用";

            if (currentStatus2 !== previousStatus2 && !isNaN(distance2) && distance2 > 0) {
                let elapsedTime2 = time2 ? Math.floor((Date.now() - time2) / 1000) : 0; // 経過時間を計算

                //表示更新
                $('#print').text(currentStatus2);
                obniz.display.clear();
                obniz.display.print(currentStatus2);

                if (currentStatus2 === "使用中") {
                    time2 = Date.now(); // 使用開始時間を記録
                }

                console.log("送信準備：", {
                    No: sensorNo2,
                    Time: elapsedTime2,
                    Status: currentStatus2,
                    Distance: distance2
                });
                // 送信処理
                $.post("save_data.php", {
                    No: sensorNo2,
                    Time: elapsedTime2,
                    Status: currentStatus2,
                    Distance: distance2
                }, function (data) {
                    console.log("送信完了", data);
                });

                previousStatus2 = currentStatus2; // 現在の状態を保存
            }
        }, 1000);
    };
});
