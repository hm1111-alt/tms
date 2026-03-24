 <html>
    <head>
        <style>
            @font-face {
                    font-family: "Arial Narrow";
                    src: url(<?php echo WRITEPATH.'fonts\ARIALN.TTF'; ?>) format("truetype");
                    font-weight: normal;
                    font-style: normal;
            }
            @font-face {
                    font-family: "Arial Narrow";
                    src: url(<?php echo WRITEPATH.'fonts\ARIALNB.TTF'; ?>) format("truetype");
                    font-weight: bold;
                    font-style: normal;
            }
            body { font-family: "Arial Narrow"; }
            h1.main { font-weight: bold; 
                font-family: "Arial Narrow"; 
                font-style: italic;

            }
            h1.arial { font-weight: bold; 
                font-family: "Arial"; 
                font-style: italic;
            }
        </style>
    </head>
    <body><?php echo WRITEPATH.'fonts\arialnarrow.ttf'; ?>
        <br>
        <?php echo base_url('writable/fonts/arialnarrow.ttf'); ?>
        <h1 class="main">This is Arial Narrow Bold</h1>
        <h1 class="arial">This is Arial only</h1>
        <p>This is Arial Narrow Regular font in a PDF.</p>

        <div style="text-align: center; font-size: 20px; font-weight: bold; margin-top: 30px; margin-bottom: 10px; font-family: 'Helvetica';">
            APPLICATION FOR LEAVE
        </div>
        <div style="text-align: center; font-size: 20px; font-weight: bold; margin-top: 30px; margin-bottom: 10px; font-family: 'Arial Narrow';">
            APPLICATION FOR LEAVE
        </div>

    </body>
</html>