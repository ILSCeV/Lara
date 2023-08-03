export function addProgressBars(){
    document.querySelectorAll("div[data-total-shifts]").forEach( (div : HTMLDivElement) => {
  
      // Absolute values
      const total = parseInt(div.dataset.totalShifts);
      if(! (total>0)) return; // No shifts, don't display a progress bar
      const free = parseInt(div.dataset.emptyShifts);
      const freeOptional = parseInt(div.dataset.optEmptyShifts);
      const taken = total - free - freeOptional;
  
      // Percentages 0-100
      const takenPerc = 100 * taken/total;
      const freePerc = 100 * free/total;
      const freeOptPerc = 100 * freeOptional/total;
      
      // Create the progress bar DIVs
      const progress = `
      <div class="progress-stacked" style="height:6px;  box-shadow: 0 0 1px 1px #ffffff; margin-bottom:3px">
        <div class="progress" role="progressbar" title="${taken}" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Taken shifts" aria-valuenow="${takenPerc}" aria-valuemin="0" aria-valuemax="100" style="width: ${takenPerc}%">
          <div class="progress-bar bg-success"></div>
        </div>
        <div class="progress" role="progressbar" title="${free}" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Free" aria-valuenow="${freePerc}" aria-valuemin="0" aria-valuemax="100" style="width: ${freePerc}%">
          <div class="progress-bar bg-danger" ></div>
        </div>
        <div class="progress" role="progressbar" title="${freeOptional}"  data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Free (opt)" aria-valuenow="${freeOptPerc}" aria-valuemin="0" aria-valuemax="100" style="width: ${freeOptPerc}%">
          <div class="progress-bar bg-warning"></div>
        </div>
      </div>`;
      const progressBar = document.createElement("div");
      progressBar.innerHTML = progress;
  
      // Add everything to the card, as first child
      div.prepend(progressBar);
    });
  }