<?php

function RSS_mark($lines)
{
    global $rssname, $pageurl, $rssurl, $rsscreator;

    $seq = '';
    $about = '';
    for ($i = 0; $i < count($lines); $i++) {

        $lines[$i] = strip_tags($lines[$i], '<>');
        $data = explode("<>", $lines[$i]);

        if ($data[0]) {

            $seq .= "\t\t<rdf:li rdf:resource=\"$pageurl$data[0].html\" />\n";

            $datearray = explode(" ", $data[2]);
            $datedata = explode("/", $datearray[0]);
            $rssdate = "$datedata[0]-" . str_pad($datedata[1], 2, '0', STR_PAD_LEFT) . "-" . str_pad($datedata[2], 2, '0', STR_PAD_LEFT) . "T$datearray[1]";

            $about .= <<<EOD
<item rdf:about="$pageurl$data[0].html">
<title>$data[3]</title>
<link>$pageurl$data[0].html</link>
<content:encoded><![CDATA[$data[4]]]></content:encoded>
<dc:creator>$rsscreator</dc:creator>
<dc:date>${rssdate}:00+09:00</dc:date>
</item>
EOD;
        }
    }


    $rss = <<<EOD
<?xml version="1.0" encoding="utf-8"?>

<rdf:RDF xml:lang="ja" xmlns="http://purl.org/rss/1.0/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:content="http://purl.org/rss/1.0/modules/content/">

<channel rdf:about="$rssurl">

<title>$rssname</title>
<link>$pageurl</link>
<description>$rssname</description>
<dc:language>ja-jp</dc:language>

<items>
<rdf:Seq>
$seq
</rdf:Seq>
</items>

</channel>

$about


</rdf:RDF>
EOD;

    return $rss;

}


?>