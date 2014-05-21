<?php


$stopwords = "able,about,above,according,accordingly,across,actually,after,afterwards,again,against,ain't,all,allow,allows,almost,alone,along,already,also,although,always,am,among,amongst,an,and,another,any,anybody,anyhow,anyone,anything,anyway,anyways,anywhere,apart,appear,appreciate,appropriate,are,aren't,around,as,aside,ask,asking,associated,at,available,away,awfully,be,became,because,become,becomes,becoming,been,before,beforehand,behind,being,believe,below,beside,besides,best,better,between,beyond,both,brief,but,by,c'mon,c's,came,can,can't,cannot,cant,cause,causes,certain,certainly,changes,clearly,co,com,come,comes,concerning,consequently,consider,considering,contain,containing,contains,corresponding,could,couldn't,course,currently,definitely,described,despite,did,didn't,different,do,does,doesn't,doing,don't,done,down,downwards,during,each,edu,eg,eight,either,else,elsewhere,enough,entirely,especially,et,etc,even,ever,every,everybody,everyone,everything,everywhere,ex,exactly,example,except,far,few,fifth,first,five,followed,following,follows,for,former,formerly,forth,four,from,further,furthermore,get,gets,getting,given,gives,go,goes,going,gone,got,gotten,greetings,had,hadn't,happens,hardly,has,hasn't,have,haven't,having,he,he's,hello,help,hence,her,here,here's,hereafter,hereby,herein,hereupon,hers,herself,hi,him,himself,his,hither,hopefully,how,howbeit,however,i'd,i'll,i'm,i've,ie,if,ignored,immediate,in,inasmuch,inc,indeed,indicate,indicated,indicates,inner,insofar,instead,into,inward,is,isn't,it,it'd,it'll,it's,its,itself,just,keep,keeps,kept,know,knows,known,last,lately,later,latter,latterly,least,less,lest,let,let's,like,liked,likely,little,look,looking,looks,ltd,mainly,many,may,maybe,me,mean,meanwhile,merely,might,more,moreover,most,mostly,much,must,my,myself,name,namely,nd,near,nearly,necessary,need,needs,neither,never,nevertheless,new,next,nine,no,nobody,non,none,noone,nor,normally,not,nothing,novel,now,nowhere,obviously,of,off,often,oh,ok,okay,old,on,once,one,ones,only,onto,or,other,others,otherwise,ought,our,ours,ourselves,out,outside,over,overall,own,particular,particularly,per,perhaps,placed,please,plus,possible,presumably,probably,provides,que,quite,qv,rather,rd,re,really,reasonably,regarding,regardless,regards,relatively,respectively,right,said,same,saw,say,saying,says,second,secondly,see,seeing,seem,seemed,seeming,seems,seen,self,selves,sensible,sent,serious,seriously,seven,several,shall,she,should,shouldn't,since,six,so,some,somebody,somehow,someone,something,sometime,sometimes,somewhat,somewhere,soon,sorry,specified,specify,specifying,still,sub,such,sup,sure,t's,take,taken,tell,tends,th,than,thank,thanks,thanx,that,that's,thats,the,their,theirs,them,themselves,then,thence,there,there's,thereafter,thereby,therefore,therein,theres,thereupon,these,they,they'd,they'll,they're,they've,think,third,this,thorough,thoroughly,those,though,three,through,throughout,thru,thus,to,together,too,took,toward,towards,tried,tries,truly,try,trying,twice,two,un,under,unfortunately,unless,unlikely,until,unto,up,upon,us,use,used,useful,uses,using,usually,value,various,very,via,viz,vs,want,wants,was,wasn't,way,we,we'd,we'll,we're,we've,welcome,well,went,were,weren't,what,what's,whatever,when,whence,whenever,where,where's,whereafter,whereas,whereby,wherein,whereupon,wherever,whether,which,while,whither,who,who's,whoever,whole,whom,whose,why,will,willing,wish,with,within,without,won't,wonder,would,would've,wouldn't,yes,yet,you,you'd,you'll,you're,you've,your,yours,yourself,yourselves,zero,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";

$stopwords = explode(",", $stopwords);


/* Builds an array of words with all stop words filtered out. (case-insensitive) */

function filter_stopwords($words, $stopwords) {

    foreach ($words as $pos => $word) {
        if (!in_array(strtolower($word), $stopwords, TRUE)) {
            $filtered_words[$pos] = $word;
        }
    }

    return $filtered_words;

}


/* Builds an array of words as keys and their frequency as the value. (case-insensitive) */

function word_freq($words) {

    $frequency_list = array();

    foreach ($words as $pos => $word) {

        $word = strtolower($word);
        if (array_key_exists($word, $frequency_list)) {
            ++$frequency_list[$word];
        }
        else {
            $frequency_list[$word] = 1;
        }

    }

    return $frequency_list;

}


/* Optional function to filter out words below a specific frequency. */

function freq_filter($words, $filter) {

    return array_filter(
                            $words,
                            function($v) use($filter) {
                                if ($v >= $filter) return $v;
                            }
                        );

}


/* Builds the word cloud and returns a string containing a div of the word cloud. */

function word_cloud($words, $div_size = 400) {

    $tags = 0;
    $cloud = "<div style=\"width: {$div_size}px\">";
    
    /* This word cloud generation algorithm was taken from the Wikipedia page on "word cloud"
       with some minor modifications to the implementation */
    
    /* Initialize some variables */
    $fmax = 96; /* Maximum font size */
    $fmin = 8; /* Minimum font size */
    $tmin = min($words); /* Frequency lower-bound */
    $tmax = max($words); /* Frequency upper-bound */

    foreach ($words as $word => $frequency) {
    
        if ($frequency > $tmin) {
            $font_size = floor(  ( $fmax * ($frequency - $tmin) ) / ( $tmax - $tmin )  );
            /* Define a color index based on the frequency of the word */
            $r = $g = 0; $b = floor( 255 * ($frequency / $tmax) );
            $color = '#' . sprintf('%02s', dechex($r)) . sprintf('%02s', dechex($g)) . sprintf('%02s', dechex($b));
        }
        else {
            $font_size = 0;
        }
        
        if ($font_size >= $fmin) {
            $cloud .= "<span style=\"font-size: {$font_size}px; color: $color;\">$word</span> ";
            $tags++;
        }
        
    }
    
    $cloud .= "</div>";
    
    return array($cloud, $tags);
    
}


if (1) {

    /* If the request verb is POST and the appropriate field is supplied and isn't
       empty, we will attempt to generate the word cloud. */
    $apoorv="apoorv vikram singh is great";
    $text = $apoorv; /* Get the posted text */
    
    $words = str_word_count($text, 1); /* Generate list of words */
    $word_count = count($words); /* Word count */
    $unique_words = count( array_unique($words) ); /* Unique word count */

    $words_filtered = filter_stopwords($words, $stopwords); /* Filter out stop words from the word list */
    $word_frequency = word_freq($words_filtered); /* Build a word frequency list */
    /* Optionally, you can filter out words below a specific frequency... Uncomment the line below to do so */
    /* $word_frequency = freq_filter($word_frequency, 3); */

    $word_c = word_cloud($word_frequency); /* Generate a word cloud and get number of tags */
    $word_cloud = $word_c[0]; /* The word cloud */
    $tags = $word_c[1]; /* The number of tags in the word cloud*/

}
else {

    /* Otherwise there is nothing to do... */

    $text = "";
    
    $word_count = $unique_words = $tags = 0;
    $word_cloud = null;
    
}


/* --- END PHP CODE --- */


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
</head>

<body>

    

    
    
    <?php echo $word_cloud; ?>

</body>
</html>
