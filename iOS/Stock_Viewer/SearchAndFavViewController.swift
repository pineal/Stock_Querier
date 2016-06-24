//
//  ViewController.swift
//  Stock_Viewer
//
//  Created by Hesen Zhang on 5/22/16.
//  Copyright © 2016 Hesen Zhang. All rights reserved.
//

import UIKit
import Toast_Swift

class SearchAndFavViewController: UIViewController {
    
    @IBOutlet weak var inputText: UITextField!
    var stock: Stock?
    @IBAction func getQuote(sender: UIButton) {
        self.view.endEditing(true)
        if inputValidation(inputText.text!) {
            
            self.view.makeToastActivity(.Center)
            stock = Stock(__symbol: inputText.text!)
            dispatch_async(stock!.workingQueue) { () -> Void in
                dispatch_semaphore_wait(self.stock!.Singnal1, DISPATCH_TIME_FOREVER)
                dispatch_async(dispatch_get_main_queue()) {
                    self.performSegueWithIdentifier("testSegue", sender: sender)
                    
                    self.view.hideToastActivity()
                }
            }
        }
    }
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        let segmentedController: SegmentedController = segue.destinationViewController as! SegmentedController
        segmentedController.stock = self.stock
        //detailedVC.chartImgData = self.stock?.getStockChartData()
        //
   //     print("1")
        //print(detailedVC.chartImgData)
     //   print("2")
       // print(self.stock.getStockChartData())
        //print(detailedVC?.chartImgData)
        //detailedVC?.stockChartImageView.image = UIImage(data: (self.stock.getStockChartData()))
        //detailedVC.stockChartImageView.backgroundColor = UIColor.blackColor()
    }
    
    func inputValidation(symbol: String) -> Bool{
        if symbol == "" {
            //emptyInputError();
            return false;
        }
        return true;
    }
    
    
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
}

