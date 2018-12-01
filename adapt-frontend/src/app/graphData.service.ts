import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable} from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { LogData } from './logs/logs.component';


@Injectable()
export class GraphDataService {
    logdataObservable: Observable<LogData[]>;
    private messageSource = new BehaviorSubject<string>("default message");
    currentMessage = this.messageSource.asObservable();
    constructor(private httpClient: HttpClient){

    }
    //maybe rename this to get "all" no param data
    callAPI() {
        return this.httpClient.get('https://adapt.mns.llc/api-aggregate.php');
       //first link return this.httpClient.get('https://adapt.mns.llc/api-time-deliminated.php?start=1542517200&end=1542648600');
       // return this.httpClient.get('https://adapt.mns.llc/api-time-deliminated.php?start=1541958310&end=1541958540'); //second link -> lazarus
       //return this.httpClient.get('https://adapt.mns.llc/api-time-deliminated.php?start=1543202100&end=1543202340'); //third link -> turla
    }

    getTimeslice(start, end) {
        return this.httpClient.get('https://adapt.mns.llc/api-time-deliminated.php?start='+start+'&end=' + end); //second link -> lazarus
    }

    //maybe make a fucntion that takes an start,end params, then makes a call

}