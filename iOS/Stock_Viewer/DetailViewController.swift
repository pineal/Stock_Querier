//
//  DetailViewController.swift
//  Stock_Viewer
//
//  Created by Hesen Zhang on 5/24/16.
//  Copyright © 2016 Hesen Zhang. All rights reserved.
//

//import Cocoa
import UIKit

class DetailViewController: UIViewController, UITableViewDataSource, UITableViewDelegate  {
    
    var stock:Stock?
    @IBOutlet weak var stockChartImageView: UIImageView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
    }
    
    override func shouldAutorotate() -> Bool {
        return false
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {

            let cell = tableView.dequeueReusableCellWithIdentifier("cell", forIndexPath: indexPath)
            cell.textLabel?.text = stock?.getStockDetailTitle()[indexPath.row]
            cell.detailTextLabel?.text = stock?.getStockDetailContent()[indexPath.row]
            return cell
    }
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
            return 11
        
    }
    
    func tableView(tableView: UITableView, heightForRowAtIndexPath indexPath: NSIndexPath) -> CGFloat {
            return 40


    }
    
    
    func tableView(tableView: UITableView, titleForHeaderInSection section: Int) -> String? {
            return "Stock Details"

          }
    
    func numberOfSectionsInTableView(tableView: UITableView) -> Int {
        return 1;
    }
    

}
