<?php
$host = 'HostName Or Host IP Address';
$db   = 'Database Name';
$user = 'Database Access User Name';
$pass = 'Database Access Password';
$table = 'Table Name';
$field_name = 'Field Name';
$charset = 'Database Charset (utf-8, utf8mb4, iso-2022-jp...etc)';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['data']) && !empty($_POST['data'])) {
        $data = $_POST['data'];
        foreach($data as $d) {
            if(!empty($d['value']) && $d['value'] >= 0) {
                $stmt = $pdo->prepare("UPDATE $table SET $field_name = :value WHERE id = :id");
                $stmt->execute(['value' => $d['value'], 'id' => $d['id']]);
            }
        }
    }
    exit;
}

$sql = <<<EOF
    SELECT id, domainName, $field_name
    FROM $table tb
    WHERE $field_name IS NULL
    ORDER BY id DESC;
EOF;

$stmt = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
        <title>空欄の入力</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.1.1/css/all.css">
        <style>
            body {
                margin: 0;
                padding: 0;
            }

            header {
                width: 100%;
                position: fixed;
                left: 0;
                top: 0;
                margin: 0;
                padding: 20px;
                height: 70px;
                background: #61bef9;
            }

            h1 {
                margin: 0;
            }

            main {
                padding: 120px 0 20px 20px;
            }

            table {
                border-collapse: collapse;
            }

            input#UR {
                padding: 5px;
                border-radius: 5px;
            }

            .domainName {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            tbody td {
                padding: 10px;
                border: 1px solid #ccc;
            }

            footer {
                width: 100%;
                position: fixed;
                left: 0;
                bottom: 0;
                padding: 5px 10px;
                height: 50px;
                background: #61bef9;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>テーブル空欄の入力用</h1>
            <div class="save">
                <button type="button" name="save">保存</button>
            </div>
        </header>
        <main>
            <h2>残り <?=$stmt->rowCount();?>件</h2>
            <table>
                <thead>
                    <tr>
                        <th>空欄フィールド</th>
                        <th>ドメイン名</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $domains):?>
                        <tr>
                            <td>
                                <input type="text" name="value" id="value" value="<?=$domains[$field_name];?>" data-id="<?=$domains['id'];?>">
                            </td>
                            <td>
                                <div class="domainName">
                                    <p><?=$domains['domainName'];?></p>
                                    <span><i class="fa-solid fa-clipboard copy"></i></span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>空欄フィールド</th>
                        <th>ドメイン名</th>
                    </tr>
                </tfoot>
            </table>
        </main>
        <footer>
            <p>copyright 2022-<?=date('Y');?> reusedomain.com All Rights Reserved.</p>
        </footer>
        <script type="text/javascript" src="/javascript/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $(function(){
                let doubleFlag = true;
                $('.copy').on('click', function(){
                    if (doubleFlag){
                        doubleFlag = false;
                        let domain = $(this).closest('div').children('p').text().replace(/\s+/g, '');
                        let textarea = $('<textarea></textarea>');
                        textarea.text(domain);
                        $(this).append(textarea);
                        textarea.select();
                        document.execCommand('copy');
                        textarea.remove();
                        let text = $('<div style="color: red;font-size: .6em;">コピーしました</div>');
                        $(this).closest('div').append(text);
                        text.show().delay(1000).fadeOut(200).queue(function(){
                            this.remove();
                            doubleFlag = true;
                        });
                    }
                    return false;
                });

                $("button[name='save']").click(function() {
                    var data = [];
                    $("td > input[type='text']").each(function() {
                        var val = $(this).val();
                        if(val !== '') {
                            data.push({
                                id: $(this).data('id'),
                                value: val
                            });
                        }
                    });

                    if(data.length > 0) {
                        $.post("", {data: data}, function (response) {
                            location.reload();
                        });
                    }
                });
            });
        </script>
    </body>
</html>
