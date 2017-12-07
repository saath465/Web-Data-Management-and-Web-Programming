package xpath;

import javax.xml.xpath.*;
import org.xml.sax.InputSource;
import org.w3c.dom.*;

public class xpath {
	 static void print ( Node e ) {
			if (e instanceof Text)
			    System.out.print(((Text) e).getData());
			else {
			    NodeList c = e.getChildNodes();
			    System.out.print("<"+e.getNodeName());
			    NamedNodeMap attributes = e.getAttributes();
			    for (int i = 0; i < attributes.getLength(); i++)
				System.out.print(" "+attributes.item(i).getNodeName()
						 +"=\""+attributes.item(i).getNodeValue()+"\"");
			    System.out.print(">");
			    for (int k = 0; k < c.getLength(); k++)
				print(c.item(k));
			    System.out.print("</"+e.getNodeName()+">");
			}
		    }

		    static void eval ( String query, String document ) throws Exception {
			XPathFactory xpathFactory = XPathFactory.newInstance();
			XPath xpath = xpathFactory.newXPath();
			InputSource inputSource = new InputSource(document);
			NodeList result = (NodeList) xpath.evaluate(query,inputSource,XPathConstants.NODESET);
			System.out.println("XPath query: "+query);
			
			for (int i = 0; i < result.getLength(); i++)
			    print(result.item(i));
			System.out.println();
		    
		    }

		    public static void main ( String[] args ) throws Exception
		    {
		      System.out.println("Query 1: Print the titles of all articles whose one of the authors is David Maier.\n");
			  eval("/SigmodRecord/issue/articles/article/title[../authors/author/text()='David Maier']","SigmodRecord.xml");
			//2
			  System.out.println("\nQuery 2: Print the titles of all articles whose first author is David Maier.\n");
		      eval("/SigmodRecord/issue/articles/article/title[../authors/author[@position='00']/text()='David Maier']","SigmodRecord.xml");
		    //3
		      System.out.println("\nQuery 3: Print the titles of all articles whose authors include David Maier and Stanley B. Zdonik\n");
		     eval("/SigmodRecord/issue/articles/article/title[../authors/author/text()='David Maier' and text()='Stanley B. Zdonik']","SigmodRecord.xml");
		    //4
		     System.out.println("\nQuery 4: Print the titles of all articles in volume 19/number 2.\n");
		     eval("/SigmodRecord/issue[volume='19' and number='2']/articles/article/title","SigmodRecord.xml");
		    //5
		     System.out.println("\nQuery 5: Print the titles and the init/end pages of all articles in volume 19/number 2 whose authors include Jim Gray.\n");
		     System.out.println("\t\t For Title\n");
		    eval("/SigmodRecord/issue[volume='19' and number='2']/articles/article/title[../authors/author/text='Jim Gray']","SigmodRecord.xml");
		    System.out.println("\t\t For initPage\n");
		    eval("/SigmodRecord/issue[volume='19' and number='2']/articles/article/initPage[../authors/author/text='Jim Gray']","SigmodRecord.xml");
		    
		    System.out.println("\t\t For endPage\n");
		    eval("/SigmodRecord/issue[volume='19' and number='2']/articles/article/endPage[../authors/author/text='Jim Gray']","SigmodRecord.xml");
		    
		    //6
		    System.out.println("\nQuery 6: Print the volume and number of all articles whose authors include David Maier.\n");
		    System.out.println("\t\t For Volume\n");
		    eval("/SigmodRecord/issue/volume[../articles/article/authors/author='David Maier']","SigmodRecord.xml");
		    
		    System.out.println("\t\t For number\n");
		    eval("/SigmodRecord/issue/number[../articles/article/authors/author='David Maier']","SigmodRecord.xml");
		    }
}
