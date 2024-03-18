{pdf/pdf_inc.i "THIS-PROCEDURE"}
DEFINE VARIABLE Vitems AS INTEGER NO-UNDO.
DEFINE VARIABLE Vrow AS INTEGER NO-UNDO.
DEFINE VARIABLE Vcat-desc AS CHARACTER EXTENT 4 FORMAT "X(40)" NO-UNDO.
/* Create stream for new PDF file */
RUN pdf_new ("Spdf", "itemlist.pdf").
/* activate tags to put a link on the item name */
RUN pdf_set_parameter("Spdf","UseTags","TRUE").
/* no need to define the fonts for the tags:
 we are only using <url=> which does not change the font */
/* Load Bar Code Font */

/* Load an image that we will use to show how to place onto the page */
RUN pdf_load_image ("Spdf", "Product", "/admcom/relat/temp3.jpg").
/* Set Document Information */
RUN pdf_set_info("Spdf","Author","pdfInclude team").
RUN pdf_set_info("Spdf","Subject","Inventory").
RUN pdf_set_info("Spdf","Title","Item Catalog").
RUN pdf_set_info("Spdf","Keywords","Item Catalog Inventory").
RUN pdf_set_info("Spdf","Creator","PDFinclude 5.0").
RUN pdf_set_info("Spdf","Producer","itemlist.p").
/* Instantiate a New Page */
RUN new_page.
/* Loop through appropriate record set */
FOR EACH teste.ITEM NO-LOCK BREAK BY ItemNum:
 Vitems = Vitems + 1.
 RUN display_item_info.
 /* Process Record Counter */
 IF Vitems = 2 THEN
 RUN new_page.
END.
RUN pdf_close("Spdf").
IF RETURN-VALUE > '' THEN
 MESSAGE RETURN-VALUE
 VIEW-AS ALERT-BOX ERROR BUTTONS OK.
/* -------------------- INTERNAL PROCEDURES -------------------------- */
PROCEDURE display_item_info:
 /* Draw main item Box */
 RUN pdf_stroke_fill("Spdf",.8784,.8588,.7098).
 RUN pdf_rect ("Spdf", pdf_LeftMargin("Spdf"), Vrow,
 pdf_PageWidth("Spdf") - 20 , 110,1.0).
 /* Draw Smaller box (beige filled) to contain Category Description */
 RUN pdf_rect ("Spdf", 350, Vrow + 10, 240, 45,1.0).
 /* Draw Smaller box (white filled) to contain Item Picture (when avail) */
 RUN pdf_stroke_fill("Spdf",1.0,1.0,1.0).
 RUN pdf_rect ("Spdf", pdf_LeftMargin("Spdf") + 10, Vrow + 10,
 pdf_LeftMargin("Spdf") + 100 , 90,1.0).
 /* Place Link around the Image Box */
 RUN pdf_link ("Spdf",
 20, pdf_GraphicY("Spdf") - 90 ,
 130, pdf_GraphicY("Spdf"),
"http://www.example.com?ItemNum=" + STRING(teste.ITEM.ItemNum),
 1, 0, 0, 1, "P").
 /* Display a JPEG picture in the First Box of each Frame */
 IF Vitems >= 1 THEN DO:
 RUN pdf_place_image ("Spdf","Product",
 pdf_LeftMargin("spdf") + 12,
 pdf_PageHeight("Spdf") - Vrow - 13,
 pdf_LeftMargin("Spdf") + 95, 86).
 END.
 /* Display Labels with Bolded Font */
 RUN pdf_set_font("Spdf","Courier-Bold",10.0).
 RUN pdf_text_xy ("Spdf","Part Number:", 140, Vrow + 90).
 RUN pdf_text_xy ("Spdf","Part Name:", 140, Vrow + 80).
 RUN pdf_text_xy ("Spdf","Category 1:", 140, Vrow + 40).
 RUN pdf_text_xy ("Spdf","Category 2:", 140, Vrow + 30).
 RUN pdf_text_xy ("Spdf","Qty On-Hand:", 350, Vrow + 90).
 RUN pdf_text_xy ("Spdf","Price:", 350, Vrow + 80).
 RUN pdf_text_xy ("Spdf","Category Description:", 350, Vrow + 60).
 /* Display Fields with regular Font */
 RUN pdf_set_font("Spdf","Courier",10.0).
 RUN pdf_text_xy ("Spdf",STRING(ITEM.ItemNuM), 230, Vrow + 90).
 RUN pdf_text_xy ("Spdf", /* also create a hyperlink on the item name */
 "<url=http://www.example.com?ItemNum=" + STRING(ITEM.ItemNum) + ">"
 + ITEM.ItemName + "</url>", 230, Vrow + 80).



 RUN pdf_text_xy ("Spdf",ITEM.Category1, 230, Vrow + 40).
 RUN pdf_text_xy ("Spdf",ITEM.Category2, 230, Vrow + 30).
 RUN pdf_text_xy ("Spdf",STRING(ITEM.OnHand), 440, Vrow + 90).
 RUN pdf_text_xy ("Spdf",TRIM(STRING(ITEM.Price,"$>>,>>9.99-")), 440, Vrow + 80).
 /* Now Load and Display the Category Description */
 IF Vitems <> 1 THEN DO:
 RUN pdf_text_color("Spdf",1.0,.0,.0).
 RUN pdf_text_xy ("Spdf","NO", 40, Vrow + 66).
 RUN pdf_text_xy ("Spdf","IMAGE", 40, Vrow + 56).
 RUN pdf_text_xy ("Spdf","AVAILABLE", 40, Vrow + 46).
 END.
 RUN pdf_text_color("Spdf",.0,.0,.0).
 /* Display the Product Number as a Bar Code */
 RUN pdf_text_xy ("Spdf",STRING(ITEM.ItemNum,"999999999"), 140, Vrow + 60).

 Vrow = Vrow - 120.
END. /* display_item_info */
PROCEDURE new_page:
 RUN pdf_new_page("Spdf").
 /* Reset Page Positioning etc */
 ASSIGN Vrow = pdf_PageHeight("Spdf") - pdf_TopMargin("Spdf") - 110
 Vitems = 0.
END. /* new_page */
