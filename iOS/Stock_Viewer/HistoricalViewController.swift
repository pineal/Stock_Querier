//
//  HistoricalViewController.swift
//  Stock_Viewer
//
//  Created by Hesen Zhang on 5/24/16.
//  Copyright © 2016 Hesen Zhang. All rights reserved.
//  


import UIKit
class HistoricalViewController: UIViewController {
    @IBOutlet weak var mainWebView: UIWebView!
    override func viewDidLoad() {
        super.viewDidLoad()
        let url = NSURL(string: "http://stockviewer.8nkg7yxpb7.us-west-1.elasticbeanstalk.com/query.php?api=get_hist_chart&symbol=GOOGL")
        let request = NSURLRequest(URL: url!)
        mainWebView.loadRequest(request)
    }
}