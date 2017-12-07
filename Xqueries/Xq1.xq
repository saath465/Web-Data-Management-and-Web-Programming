<List-of-Continents>
{
for $cou_name in doc("auction.xml")/site/regions/*
return {
        <continent> {name($cou_name)}
                <Total-items>{count($cou_name/item)}</Total-items>
        </continent>
       
        }
}
</List-of-Continents>
