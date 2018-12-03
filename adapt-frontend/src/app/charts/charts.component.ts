import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpClient, HttpResponse} from '@angular/common/http';
import { Observable } from 'rxjs';
import { GraphDataService } from '../graphData.service';
import { BaseChartDirective } from 'ng2-charts/ng2-charts';
import {NgbTimeStruct} from '@ng-bootstrap/ng-bootstrap';
import {FormControl} from '@angular/forms'
import { setTNodeAndViewData } from '@angular/core/src/render3/state';

export interface LogData {
  // Matching Events	Total Events	% of Events	Matching TTPs	Available TTPs	% of TTPs	Coverage	% of Coverage	Final value
  // I changed order:: final value moved up to second col.
  group: string;
  final_value: string;
  matching_events: string;
  total_events: string;
  percent_of_events: string;
  matching_ttps: string;
  available_ttps: string;
  percent_of_ttps: string;
  coverage: string;
  percent_of_coverage: string;  
}
@Component({
  selector: 'app-charts',
  templateUrl: './charts.component.html',
  styleUrls: ['./charts.component.css']
})
export class ChartsComponent implements OnInit {
  time: NgbTimeStruct = {hour: 13, minute: 30, second: 30};
  seconds = true;

  endValue = new FormControl(new Date());
  startValue = new FormControl(new Date());

  startFilter = (d: Date): boolean => {
    const day = d.getDay();
    // Prevent Saturday and Sunday from being selected.
    return true;
  }
  endFilter = (d: Date): boolean => {
    const day = d.getDay();
    // Prevent Saturday and Sunday from being selected.
    return true;
  }
  public logData = new Array();
  public aptGroupNames = new Array();
  public aptFinalValues = new Array();
  public doughnutChartType:string = 'doughnut';
  constructor(private api: GraphDataService){

  }

  public barChartOptions = {
    scaleShowVerticalLines: true,
    responsive: true
  };
  public barChartLables;
  public barChartType = 'bar';
  public barChartLegend = true;
  public barChartData = [
    {
      data: [], label: 'APT Data'
    },
  ];
  public chartColors: Array<any> = [
    { // first color
      backgroundColor: 'grey',
      borderColor: 'rgba(225,10,24,0.2)',
      pointBackgroundColor: 'rgba(225,10,24,0.2)',
      pointBorderColor: '#fff',
      pointHoverBorderColor: 'rgba(225,10,24,0.2)'
    }];
  // events
  public chartClicked(e:any):void {
    console.log(e);
  }
  public chartHovered(e:any):void {
    console.log(e);
  }
  public getGroupNames():void {
    for(var i = 0; i < 58; i ++){
      console.log(this.logData[i]['group']);
    }
  }

  public pieChartLabels:string[] = ['Collection', 'Command-and-Control', 'Credential-Access', 
          'Defense-Evasion', 'Discovery', 'Execution', 'Exfiltration', 'Initial-Access', 
          'Lateral-Movement', 'Persistence', 'Privilege-Escalation'];
  public pieChartData:number[] = [8, 10, 5, 4, 6, 1, 9, 0, 7, 2, 3];
  public pieChartType:string = 'pie';
 
  public getAPIData():void {
     // Call the API
     this.api.callAPI().subscribe((data) => {
     //console.log(data); // this should get you the body of the request. this is a DEBUG
      for( let entry in data){
        //console.log("added in NAMES: "+ data[entry]['group']);
        this.aptGroupNames.push(data[entry]['group']);
        this.aptFinalValues.push(data[entry]['final_value']);
      }
      this.barChartLables = this.aptGroupNames;
      this.barChartData[0].data = this.aptFinalValues;
    }, (err) => {
      // Do your error handling here
      console.log('error');
      alert('There was an error loading the data.');
    });
  }
  public lineChartColors:Array<any> = [
    { // grey
      backgroundColor: 'rgba(148,159,177,0.2)',
      borderColor: 'rgba(148,159,177,1)',
      pointBackgroundColor: 'rgba(148,159,177,1)',
      pointBorderColor: '#fff',
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: 'rgba(148,159,177,0.8)'
    }
  ];

  public pieChartColors:Array<any> = [
    { 
      backgroundColor: ["#00b894", "#00cec9", "#0984e3", "#6c5ce7", "#b2bec3", "#fdcb6e", "#e17055", "#d63031", "#e84393", "#2d3436", '#3c6382' ],
    }
  ];

  public selectTime(startDate, startTime, endDate, endTime) {
    var sDate = startDate.value;
    var eDate = endDate.value;

    sDate.setHours(startTime.hour);
    sDate.setMinutes(startTime.minute);
    sDate.setSeconds(startTime.second);

    eDate.setHours(endTime.hour);
    eDate.setMinutes(endTime.minute);
    eDate.setSeconds(endTime.second);

    var startTimestamp = (sDate.getTime() / 1000);
    var endTimestamp = (eDate.getTime() / 1000);
    //console.log("Start Timeslice: " + startTimestamp);
    //console.log("End time slice: " + endTimestamp);
    var self = this;

    // Call the API
    this.api.getTimeslice(startTimestamp, endTimestamp).subscribe((data:Array<any>) => {
      var finalVals = [];
      var finalNames = [];

      for( let entry in data){
        //console.log("added in NAMES: "+ data[entry]['group']);
        finalNames.push(data[entry]['group']);
        finalVals.push(data[entry]['final_value']);
      }
      this.barChartLables = finalNames;
      this.barChartData[0].data = finalVals;
    });
  }


  public lineChartLegend:boolean = true;
  public lineChartType:string = 'line';
  ngOnInit() {  
    //get data 1st!
    this.getAPIData();
  }
}
