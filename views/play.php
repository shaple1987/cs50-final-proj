<div hidden>
    <audio id="a1" preload class="tracks">
        <source id="src1" src="audio/<?= $audio["w1"] ?>" type="audio/wav">
        Your browser does not support the audio element.
    </audio>
    <audio id="a2" preload class="tracks">
        <source id="src2" src="audio/<?= $audio["w2"] ?>" type="audio/wav">
        Your browser does not support the audio element.
    </audio>
    <audio id="a3" preload class="tracks">
        <source id="src3" src="audio/<?= $audio["w3"] ?>" type="audio/wav">
        Your browser does not support the audio element.
    </audio>
</div>    

<div>
    <form method="post" action="test.php">
    <table id="history" class="t1">
        
    <tr>
        <th id='trial' colspan=3>Trial <?= $_SESSION["seq"] ?> of <?= $_SESSION["N"] ?></th>
        </tr>
    <tr>
        <th class="labels">Word 1</th>
        <th class="labels">Word 2</th>
        <th class="labels">Word 3</th>
    </tr>
    <tr id="buttons" hidden>
        <td align="center"><button type="submit" name="submit" value="0">This word does not rhyme.</button></td>
        <td align="center"><button type="submit" name="submit" value="1">This word does not rhyme.</button></td>
        <td align="center"><button type="submit" name="submit" value="2">This word does not rhyme.</button></td>
    </tr>
    </table>
    </form>
</div>

        <script>
            jQuery(document).ready(function (){
            var header = document.getElementById('trial');
            header.bgColor = '#4CAF50';
            var audioArray = document.getElementsByClassName('tracks');
            var labelArray = document.getElementsByClassName('labels');
            var N = labelArray.length;
            var i = 0;
            for(i = 0; i < N; i++){
                labelArray[i].bgColor = '#4CAF50';
            }
            var playaudio = <?= $playaudio ?>;
            if (playaudio == '1'){
                i = 0;
                var i2 = 0;
                labelArray[i2].bgColor = 'red';
                audioArray[i].play();
                for (i = 0; i < N; ++i) {
                    audioArray[i].addEventListener('ended', function(e){
                        ++i2;
                        var currentSong = e.target;
                        var next = $(currentSong).nextAll('audio');
                        if (next.length){
                            setTimeout(function (){
                                labelArray[i2 - 1].bgColor='#4CAF50';
                                labelArray[i2].bgColor='red';
                                $(next[0]).trigger('play');
                            }, 1000);
                        } else { // all audios have been played
                            setTimeout(function (){
                                labelArray[N - 1].bgColor='#4CAF50';
                                $("#buttons").toggle();
                            }, 1000);  
                        }
                    });   
                }
            } else { // skip playing audio, show response buttons directly
                $("#buttons").toggle();
            }
        });
        //window.onbeforeunload = function() { return "You work will be lost."; };
    </script>
