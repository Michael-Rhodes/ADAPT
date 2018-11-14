import { Injectable } from '@angular/core';
import { BehaviorSubject} from 'rxjs';

@Injectable()
export class GraphDataService {

    private messageSource = new BehaviorSubject<string>("default message");
    currentMessage = this.messageSource.asObservable();
    constructor(){

        // want to use this to broadcast the json data taken from the logs... not yet ready...  https://www.youtube.com/watch?v=I317BhehZKM
    }
}