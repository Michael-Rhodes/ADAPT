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

    callAPI() {
        return this.httpClient.get('https://adapt.mns.llc/api-aggregate.php'); 
    }
}