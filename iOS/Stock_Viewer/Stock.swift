//
//  Stock.swift
//  Stock_Viewer
//
//  Created by Hesen Zhang on 5/23/16.
//  Copyright © 2016 Hesen Zhang. All rights reserved.
//

import Foundation
import UIKit
class Stock {
    var symbol: String
    private var detailData: NSDictionary?
    private var stockChartData: NSData?
    
    let workingQueue = dispatch_queue_create("my_queue", DISPATCH_QUEUE_CONCURRENT)
    var Singnal1 = dispatch_semaphore_create(0)
    var Singnal2 = dispatch_semaphore_create(0)
    var Singnal3 = dispatch_semaphore_create(0)
    var Singnal4 = dispatch_semaphore_create(0)
    
    init?(__symbol: String) {
        self.symbol = __symbol
        self.fetchDetailDataFromJSON(__symbol)
        self.fetchStockGraph(__symbol)
        //getNews
        //getHistoricalChart
    }
    
    func getStockChartData() -> NSData {
        return self.stockChartData!
    }
    
    func getStockDetailTitle() -> Array<String> {
        return ["Name", "Symbol", "Last Price", "Change", "Time and Date", "Market Cap", "Volume", "Change YTD", "High Price", "Low Price", "Opening Price", "Chart"]
    }
    
    func getStockDetailContent() -> Array<String> {
        var stockDetail = [String](count:11, repeatedValue: "")
        if (self.detailData!["Message"] != nil) {
            return stockDetail
        } else {
            stockDetail[0] = (self.detailData!["Name"] as! String);
            stockDetail[1] = (self.detailData!["Symbol"] as! String);
            stockDetail[2] = "$ " + String(format:"%.2f", self.detailData!["LastPrice"] as! Double);
            stockDetail[3] = String(format:"%.2f", self.detailData!["Change"] as! Double) + "(" + String(format:"%.2f", (self.detailData!["ChangePercent"] as! Double)) + "%)";
            if (self.detailData!["Change"] as! Double) < 0.0 {
                stockDetail[3] = "-" + stockDetail[3]
            } else {
                stockDetail[3] = "+" + stockDetail[3]
            }
            stockDetail[4] = (self.detailData!["Timestamp"] as! String)
            stockDetail[5] = "$ " + String(format:"%.0f", (self.detailData!["MarketCap"] as! Double)/1000000000) + " Billion";
            stockDetail[6] = String(self.detailData!["Volume"] as! Int);
            stockDetail[7] = String(format:"%.2f", self.detailData!["ChangeYTD"] as! Double) + "(" + String(format:"%.2f", (self.detailData!["ChangePercentYTD"] as! Double)) + "%)";
            if (self.detailData!["ChangeYTD"] as! Double) < 0.0 {
                stockDetail[7] = "-" + stockDetail[7]
            } else {
                stockDetail[7] = "+" + stockDetail[7]
            }
            stockDetail[8] = "$ " + String(format:"%.2f", self.detailData!["High"] as! Double);
            stockDetail[9] = "$ " + String(format:"%.2f", self.detailData!["Low"] as! Double);
            stockDetail[10] = "$ " + String(format:"%.2f", self.detailData!["Open"] as! Double);
            return stockDetail
        }
    }
    
    func fetchNews(__symbol: String) {
    }
    
    func fetchDetailDataFromJSON(__symbol: String){
        // Setup the session to make REST GET call.  Notice the URL is https NOT http!!
        let baseURL = "http://stockviewer.8nkg7yxpb7.us-west-1.elasticbeanstalk.com/query.php?api=get_quote&symbol="
        let postEndpoint: String = baseURL + __symbol
        let session = NSURLSession.sharedSession()
        let url = NSURL(string: postEndpoint)!
        
        // Make the POST call and handle it in a completion handler
        session.dataTaskWithURL(url, completionHandler: { ( data: NSData?, response: NSURLResponse?, error: NSError?) -> Void in
            // Make sure we get an OK response
            guard let realResponse = response as? NSHTTPURLResponse where
                realResponse.statusCode == 200 else {
                    print("Not a 200 response")
                    return
            }
            // Read the JSON
            do {
                if (NSString(data:data!, encoding: NSUTF8StringEncoding) != nil) {
                  //  print (NSString(data:data!, encoding: NSUTF8StringEncoding))
                    self.detailData = try NSJSONSerialization.JSONObjectWithData(data!, options: NSJSONReadingOptions.MutableContainers) as? NSDictionary
                    dispatch_async(self.workingQueue) { () -> Void in
                        dispatch_semaphore_signal(self.Singnal1)
                    }
                }
            } catch {
                //self.view.hideToastActivity()
                print("bad things happened")
            }
        }).resume()
    }
    
    
    func fetchStockGraph(__symbol: String){
        let url = "http://chart.finance.yahoo.com/t?lang=en-US&width=800&height=450&s=" + __symbol
        let checkedUrl = NSURL(string: url)!
        downloadImage(checkedUrl)
        
    }
    
    func getDataFromUrl(url:NSURL, completion: ((data: NSData?, response: NSURLResponse?, error: NSError? ) -> Void)) {
        NSURLSession.sharedSession().dataTaskWithURL(url) { (data, response, error) in
            completion(data: data, response: response, error: error)
            }.resume()
    }
    
    func downloadImage(url: NSURL){
        print("Image Download Started")
        getDataFromUrl(url) { (data, response, error)  in
                guard let data = data where error == nil else { return }
                self.stockChartData = data
                print("Download Finished")

        }
    }

}