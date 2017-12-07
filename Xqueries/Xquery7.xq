for $i in doc('auction.xml')/site/regions//item
order by $i/name
return
<item>
{
        {$i/name},
        {$i/location}
}
</item>