
                                                                  
    <!-- Swiper Container -->
    <div class="swiper-container">
        <div class="controls">
            <button id="stop">Stop</button>
            <button id="start">Start</button>
        </div>
        <div class="swiper-wrapper">
            <?php 
            $filedir = 'public/assets/files/memos';
            if(@$memos){
                foreach($memos as $memo){ ?>
            
                    <div class="swiper-slide">
                        <embed src="<?php echo base_url($filedir.'/'.$memo->memo_file_name); ?>" style="height: 90%; width: 100%;">
                    </div>
                    
            <?php }
            } ?>
        </div>

        <!-- Pagination -->
        <div class="swiper-pagination"></div>

        <!-- Navigation Buttons -->
        <!--<div class="swiper-button-next"></div>-->
        <!--<div class="swiper-button-prev"></div>-->
        
    <!-- Stop/Start Buttons -->
    </div>
    
    
    
        // Initialize Swiper
        const swiper = new Swiper('.swiper-container', {
            direction: 'horizontal',
            autoplay: {
                delay: 5000,
                disableOnInteraction: true, // Stops autoplay on user interaction
            },
            loop: true,  // Infinite loop
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });
        
        // Stop Button
        document.getElementById('stop').addEventListener('click', () => {
            swiper.autoplay.stop();
        });

        // Start Button
        document.getElementById('start').addEventListener('click', () => {
            swiper.autoplay.start();
        });
        